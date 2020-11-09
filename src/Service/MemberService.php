<?php


namespace App\Service;


use App\Entity\Member;
use App\Entity\MemberMembership;
use App\Entity\Membership;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MemberService
{
    /** @var RequestStack */
    private $requestStack;

    /** @var EntityManagerInterface */
    private $em;

    /** @var ValidatorInterface */
    private $validator;

    public function __construct(
        RequestStack $requestStack,
        EntityManagerInterface $em,
        ValidatorInterface $validator)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
        $this->validator = $validator;
    }

    /**
     * @return Member
     * @throws ConflictHttpException
     */
    public function getMemberFromRequest(): Member
    {
        $request = $this->requestStack->getCurrentRequest();

        $memberRepo = $this->em->getRepository(Member::class);
        $email = trim(strip_tags($request->get('email')));

        /** @var Member $member */
        if ($member = $memberRepo->findOneBy(['email' => $email])) {
            if (!$member->getPendingMemberships()->isEmpty()) {
                throw new UnprocessableEntityHttpException('Pending membership exists');
            }

            if (!$member->getActiveMemberships()->isEmpty()) {
                throw new ConflictHttpException('Active membership exists');
            }

        } else {
            $member = new Member();
        }

        $dob = $request->get('dateOfBirth');
        $dob = \DateTime::createFromFormat('Y-m-d', $dob);

        $member
            ->setGivenNames(trim(strip_tags($request->get('givenNames'))))
            ->setSurname(trim(strip_tags($request->get('surname'))))
            ->setDateOfBirth($dob !== false ? $dob : null)
            ->setEmail(trim(strip_tags($request->get('email'))))
            ->setNewsletterAccepted($request->get('newsletterAccepted') === 'true')
            ->setTermsAccepted($request->get('termsAccepted') === 'true')
            ->setMobileNumber(str_replace(' ', '', trim(strip_tags($request->get('mobileNumber')))))
            ->setPostcode(str_replace(' ', '', trim(strip_tags($request->get('postcode')))));

        return $member;
    }

    /**
     * @param Member $member
     * @return bool
     * @throws BadRequestHttpException
     */
    public function validateMember(Member $member): bool
    {
        $errors = $this->validator->validate($member);

        if (count($errors) > 0) {
            $invalidProperty = $errors->get(0)->getPropertyPath();
            throw new BadRequestHttpException(sprintf('Invalid Property: "%s"', $invalidProperty));
        }

        if (preg_match("/(\+447)\d{9}$/", $member->getMobileNumber()) !== 1) {
            throw new BadRequestHttpException(sprintf('Invalid Property: "%s"', $member->getMobileNumber()));
        }

        $postcodeLength = strlen($member->getPostcode());

        if ($postcodeLength < 5 || $postcodeLength > 7) {
            throw new BadRequestHttpException(sprintf('Invalid Property: "%s"', $member->getPostcode()));
        }

        $minAge = date_create()->setTime(0, 0)->modify('-18 years');
        if ($member->getDateOfBirth() > $minAge) {
            throw new BadRequestHttpException(sprintf('Invalid Property: "%s"', $member->getDateOfBirth()));

        }

        return true;
    }

    /**
     * @param Member $member
     * @return MemberMembership
     * @throws ConflictHttpException
     */
    public function getMembershipFromRequest(Member $member): MemberMembership
    {
        $request = $this->requestStack->getCurrentRequest();

        $membershipRepo = $this->em->getRepository(Membership::class);
        $membershipHash = $request->get('membershipHash');

        /** @var Membership $membership */
        if (!$membershipHash || (!$membership = $membershipRepo->findOneBy(['hash' => $membershipHash]))) {
            throw new ConflictHttpException('Invalid membershipHash');
        }

        $memberMembership = new MemberMembership();
        $memberMembership
            ->setMember($member)
            ->setMembership($membership);

        return $memberMembership;
    }
}
