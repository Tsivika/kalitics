<?php

namespace App\Manager;

use DateTime;
use App\Entity\CodePromo;
use App\Entity\Subscription;
use App\Entity\User;
use App\Entity\UserSubscription;
use App\Services\StripePayement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AccountManager extends BaseManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var StripePayement
     */
    private $stripe;

    /**
     * AccountManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param ValidatorInterface     $validator
     */
    public function __construct(EntityManagerInterface $em, ValidatorInterface $validator, StripePayement $stripe)
    {
        parent::__construct($em, UserSubscription::class, $validator);
        $this->em = $em;
        $this->stripe = $stripe;
    }

    /**
     * @param $userDetail
     * @param $userAccount
     *
     * @return false|\Stripe\Subscription
     *
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function subscribe($userDetail, $userAccount)
    {
        $userRepos = $this->em->getRepository(User::class);
        $user = $userRepos->find($userDetail['userId']);
        $subscription = $this->getSubscription($userAccount['subscription']);

        $customer = $this->stripe->createCustomer($userDetail['userEmail'], $userAccount['stripeToken']);
        $product = $this->stripe->createProduct($subscription->getTitle(), 'month', $userAccount['total_paid']);
        $coupon = null;
        if ($userAccount['reduction'] != 0) {
            $nameCoupon = $this->getCodePromo($userAccount['codePromo']);
            $coupon = $this->stripe->createCoupon($userAccount['reduction'], $nameCoupon->getName());
        }
        $subscriptionStripe = $this->stripe->createSubscription($customer, $product, $coupon);
        $user->setStripeToken($customer->id);
        $user->setEntreprise($userDetail['userEntreprise']);
        $user->setAddress($userDetail['userAddress']);

        $this->createUserSubscription($userDetail, $userAccount, $user, $subscriptionStripe->id);


    }

    /**
     * @param $userDetail
     * @param $userAccount
     * @param $user
     * @param $stripeSubID
     *
     * @return UserSubscription
     */
    public function createUserSubscription($userDetail, $userAccount, $user, $stripeSubID)
    {
        $userSubscription = new UserSubscription();
        $subRepos = $this->em->getRepository(Subscription::class);
        $sub = $subRepos->find($userAccount['subscription']);
        $user->setSubscriptionUser($sub);
        $codePromo = $this->checkCodePromo($userAccount['codePromo']);
        $userSubscription->setUser($user);
        $userSubscription->setEnabled(true);
        $userSubscription->setRenew(true);
        $userSubscription->setAmount($userAccount['total_paid']);
        $userSubscription->setBegin(new \DateTime("today"));
        $userSubscription->setEnd($this->getEndSubscription());
        $userSubscription->setSubscription($sub);
        $userSubscription->setCodePromo($codePromo);
        $userSubscription->setStripeToken($stripeSubID);
        $this->em->persist($userSubscription);
        $this->em->persist($user);
        $this->em->flush();

        return $userSubscription;
    }

    /**
     * @param $idSubscription
     *
     * @return object|null
     */
    public function getSubscription($idSubscription)
    {
        $repos = $this->em->getRepository(Subscription::class);

        return $repos->find($idSubscription);
    }

    /**
     * @return DateTime
     */
    private function getEndSubscription()
    {
        $end = new \DateTime("today");
        $end->add(new \DateInterval("P30D" ));

        return $end;
    }

    /**
     * @param $code
     *
     * @return object|null
     */
    public function getCodePromo($code)
    {
        $repos = $this->em->getRepository(CodePromo::class);
        $promo = $repos->findOneBy(['code' => $code]);

        return $promo;
    }

    /**
     * @param $code
     *
     * @return object|void|null
     */
    public function checkCodePromo($code)
    {
        if ($code === null) {
            return;
        }
        $codePromo = $this->getCodePromo($code);
        if (null === $codePromo) {
            return null;
        }
        $codePromo->setStatus(true);
        $this->em->persist($codePromo);
        $this->em->flush();

        return $codePromo;
    }
}
