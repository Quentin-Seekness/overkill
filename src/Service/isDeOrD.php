<?php

namespace App\Service;

use App\Entity\Letter;
use App\Repository\LetterRepository;

/**
 * This service will return a user object from the JWT token received
 */
class isDeOrD
{
    private $letter;
    private $vowels = ['a', 'e', 'i', 'o', 'u', 'y', 'A', 'E', 'I', 'O', 'U', 'Y'];

    public function __construct(Letter $letter)
    {
        $this->letter = $letter;
    }

    public function companyStartWithVowel(){

        $companyName = $this->letter->getCompanyName();
        //we want to know the first letter of the company Name
        $companyNameStarts = substr($companyName, 0, 1);

        foreach($this->vowels as $vowel) {
            if($companyNameStarts == $vowel) {
                return true;
            }
        }
        return false;
    }

    public function setCompanyDStatus() {

        if($this->companyStartWithVowel()) {
            //if it starts with a vowel, then it get the "1" status, wich means "d' "
            $this->letter->setCompanyDStatus(1);
        }
        else {
            $this->letter->setCompanyDStatus(0);
        }
    }

    public function jobStartWithVowel(){

        $jobName = $this->letter->getJobName();
        //we want to know the first letter of the job Name
        $jobNameStarts = substr($jobName, 0, 1);

        foreach($this->vowels as $vowel) {
            if($jobNameStarts == $vowel) {
                return true;
            }
        }
        return false;
    }

    public function setJobDStatus() {

        if($this->jobStartWithVowel()) {
            //if it starts with a vowel, then it get the "1" status, wich means "d' "
            $this->letter->setJobDStatus(1);
        }
        else {
            $this->letter->setJobDStatus(0);
        }
    }
}