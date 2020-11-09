<?php

namespace App\Controller;

use App\Entity\Member;
use App\Entity\MemberMembership;
use App\Service\MemberService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MemberController extends AbstractFOSRestController
{
    /**
     * @Rest\Post("/member")
     * @param MemberService $memberService
     * @return JsonResponse
     */
    public function addMember(MemberService $memberService): JsonResponse
    {
        $member = $memberService->getMemberFromRequest();
        $memberService->validateMember($member);
        $memberMembership = $memberService->getMembershipFromRequest($member);

        $em = $this->getDoctrine()->getManager();
        $em->persist($member);
        $em->persist($memberMembership);

        try {
            $em->flush();
        } catch (UniqueConstraintViolationException $exception) {
            throw new BadRequestHttpException('Email address in use');
        }

        $serializer = SerializerBuilder::create()->build();

        $member = $serializer->toArray(
            $member,
            SerializationContext::create()->setGroups('show_member')
        );

        $member['pendingMemberMembershipHash'] = $memberMembership->getHash();

        $serializer = SerializerBuilder::create()->build();

        $membership = $serializer->toArray(
            $memberMembership->getMembership(),
            SerializationContext::create()->setGroups('show_membership')
        );

        return $this->json($member + $membership);
    }

    /**
     * @Rest\Post("/member/confirm")
     * @param Request $request
     * @return JsonResponse
     */
    public function confirmMember(Request $request): JsonResponse
    {
        $memberMembershipHash = $request->get('memberMembershipHash');

        if (!$memberMembershipHash) {
            throw new BadRequestHttpException('Missing memberMembershipHash');
        }

        $em = $this->getDoctrine()->getManager();
        $memberMembershipRepo = $em->getRepository(MemberMembership::class);

        /** @var MemberMembership $memberMembership */
        $memberMembership = $memberMembershipRepo->findOneBy([
            'hash' => $memberMembershipHash,
            'status' => MemberMembership::STATUS_PENDING
        ]);

        if (!$memberMembership) {
            throw new BadRequestHttpException('Invalid memberMembershipHash');
        }

        $startDate = date_create();
        $membershipLength = $memberMembership->getMembership()->getMembershipType()->getLength();
        $expiryDate = date_create()->modify(sprintf('+%d days', $membershipLength));

        $memberMembership
            ->setStatus(MemberMembership::STATUS_ACTIVATED)
            ->setStartDate($startDate)
            ->setExpiryDate($expiryDate);

        $em->persist($memberMembership);
        $em->flush();

        $serializer = SerializerBuilder::create()->build();

        $data = $serializer->toArray(
            $memberMembership,
            SerializationContext::create()->setGroups(['show_membership', 'list_gyms', 'show_member'])
        );

        $data['expiry_date'] = $memberMembership->getExpiryDate()->format('d/m/Y');

        return $this->json($data);
    }

    /**
     * @Rest\Post("/member/clear-pending")
     * @param Request $request
     * @return JsonResponse
     */
    public function clearPendingMembership(Request $request): JsonResponse
    {
        if (!$email = trim(strip_tags($request->get('email')))) {
            throw new BadRequestHttpException('Invalid email');
        }

        $em = $this->getDoctrine()->getManager();
        $memberRepo = $em->getRepository(Member::class);

        /** @var Member $member */
        if (!$member = $memberRepo->findOneBy(['email' => $email])) {
            throw new BadRequestHttpException('Invalid email');
        }

        if ($pendingMemberMemberships = $member->getPendingMemberships()) {
            /** @var MemberMembership $pendingMemberMembership */
            foreach ($pendingMemberMemberships as $pendingMemberMembership) {
                $pendingMemberMembership->setStatus(MemberMembership::STATUS_REMOVED);
                $em->persist($pendingMemberMembership);
            }

            $em->flush();
        };

        return $this->json(['email' => $email]);
    }
}
