<?php


namespace App\Service;


use App\Entity\PoktValidator;
use App\Entity\Reward;
use App\Repository\PoktValidatorRepository;
use Doctrine\ORM\EntityManagerInterface;

class PoktValidatorService
{
    const TRANSACTION_TYPE_CLAIM = 'pocketcore/claim';
    const TRANSACTION_TYPE_PROOF = 'pocketcore/proof';

    const POKT_PER_RELAY = 0.0089;


    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var PoktValidatorRepository
     */
    private $repo;

    /**
     * @var PocketClient
     */
    private $pocketClient;

    /**
     * PoktValidatorService constructor.
     * @param EntityManagerInterface $em
     * @param PoktValidatorRepository $repo
     * @param PocketClient $pocketClient
     */
    public function __construct(EntityManagerInterface $em, PoktValidatorRepository $repo, PocketClient $pocketClient)
    {
        $this->em = $em;
        $this->repo = $repo;
        $this->pocketClient = $pocketClient;
    }

    public function getPoktValidator(string $validatorAddress): PoktValidator
    {
        $poktValidator = $this->repo->findOneBy(['validatorAddress' => $validatorAddress]);

        if(is_null($poktValidator))
        {
            $poktValidator = new PoktValidator();
            $poktValidator->setValidatorAddress($validatorAddress);
            $poktValidator->setTotalRewards(0);
            $this->em->persist($poktValidator);
            $this->em->flush();
        }

        return $poktValidator;
    }

    /**
     * @param PoktValidator $poktValidator
     */
    public function refreshRewards(PoktValidator $poktValidator): void
    {
        $transactions = $this->pocketClient->queryAccountTxs($poktValidator->getValidatorAddress());

        if(is_null($transactions))
        {
            return;
        }

        $rewardRepo = $this->em->getRepository(Reward::class);

        $totalRewards = 0;

        foreach ($transactions['txs'] as $transaction)
        {
            $msg = $transaction['stdTx']['msg'];
            $type = $msg['type'];
            $txId = $transaction['hash'];
            $height = $transaction['height'];

            if($type === self::TRANSACTION_TYPE_CLAIM)
            {
                // @todo - find the proof transaction that confirms the claim
                $totalProofs = $msg['value']['total_proofs'];


                $reward = $rewardRepo->findOneBy(['cliamTxId' => $txId]);

                if(is_null($reward))
                {
                    $reward = new Reward();
                    $reward->setCliamTxId($txId);
                }
                $reward->setPoktValidator($poktValidator);
                $reward->setAmount($totalProofs * self::POKT_PER_RELAY);
                $reward->setHeight($height);
                $this->em->persist($reward);

                $totalRewards += $reward->getAmount();
            }
        }

        $poktValidator->setTotalRewards($totalRewards);

        $this->em->flush();
    }
}