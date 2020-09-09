<?php


namespace App\Service;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class TimelineService
{

    const DAYS_PER_MONTH = 30;
    const DAYS_PER_WEEK = 7;

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function generate(array $steps, $startDate)
    {
        $totalStepsDays = 0;
        $sums = [];

        foreach ($steps as $step) {
            $totalStepsDays += $step->getDuration();
            $sums[] = $totalStepsDays;
        }

        $today = new DateTime();

        $result = [];

        if ($startDate > $today) {
            foreach ($steps as $step) {
                    $result[$step->getId()] = 'future';
            }
        } else {
            $diff = ($startDate->diff($today))->days;

            foreach ($steps as $key => $step) {
                if ($sums[$key] < $diff) {
                    $result[$step->getId()] = 'completed';
                } elseif ($sums[$key] > $diff && ($sums[$key] - $diff) > $step->getDuration()) {
                    $result[$step->getId()] = 'future';
                } elseif ($sums[$key] >= $diff) {
                    $result[$step->getId()] = 'in-progress';
                }
            }
        }



        return $result;
    }

    public function rearrange($steps, $newStep)
    {
        $newNumber = $newStep->getNumber();

        if ($newNumber > count($steps)) {
            $newStep->setNumber(count($steps) + 1);
        } else {
            $i = 1;
            foreach ($steps as $step) {
                if ($step->getNumber() >= $newNumber) {
                    $step->setNumber($newNumber + $i);
                    $this->entityManager->persist($step);
                    $i++;
                }
            }
        }
    }

    public function renumber($steps)
    {
        $i = 1;
        foreach ($steps as $step) {
            $step->setNumber($i);
            $this->entityManager->persist($step);
            $i++;
        }
    }

    public function convertDays($steps)
    {
        $result = [];
        foreach ($steps as $step) {
            if ($step->getDuration() >= self::DAYS_PER_MONTH) {
                $result[$step->getId()] = round($step->getDuration() / self::DAYS_PER_MONTH) . ' mois';
            } elseif ($step->getDuration() >= self::DAYS_PER_WEEK) {
                if (intval(round($step->getDuration() / 7)) === 1) {
                    $result[$step->getId()] = round($step->getDuration() / self::DAYS_PER_WEEK) . ' semaine';
                } else {
                    $result[$step->getId()] = round($step->getDuration() / self::DAYS_PER_WEEK) . ' semaines';
                }
            } else {
                if ($step->getDuration() === 1) {
                    $result[$step->getId()] = $step->getDuration() . ' jour';
                } else {
                    $result[$step->getId()] = $step->getDuration() . ' jours';
                }
            }
        }
        return $result;
    }
}
