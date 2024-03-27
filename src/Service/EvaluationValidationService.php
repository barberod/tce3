<?php

namespace App\Service;

class EvaluationValidationService
{
    /**
     * Validate create
     *
     * @param array $formData
     */
    public function validateCreateEvaluation(array $formData): array
    {
        $validationResponse = [
            'formData' => $formData,
            'validationErrors' => []
        ];

        // Req Admin

        if (!isset($formData['requiredForAdmission']) || empty($formData['requiredForAdmission'])) {
            $validationResponse['validationErrors']['requiredForAdmission'] = false;
            $validationResponse['feedback']['requiredForAdmission'] = '<div class="invalid-feedback">Required</div>';
        }

        // Institution

        if (!isset($formData['locatedUsa']) || empty($formData['locatedUsa'])) {
            $validationResponse['validationErrors']['locatedUsa'] = false;
            $validationResponse['feedback']['locatedUsa'] = '<div class="invalid-feedback">Required</div>';

        } else if ($formData['locatedUsa'] == 'Yes') {
            if (!isset($formData['institutionListed']) || empty($formData['institutionListed'])) {
                $validationResponse['validationErrors']['institutionListed'] = false;
                $validationResponse['feedback']['institutionListed'] = '<div class="invalid-feedback">Required</div>';

            } else if ($formData['institutionListed'] == 'No') {
                if (!isset($formData['institutionName']) || empty($formData['institutionName'])) {
                    $validationResponse['validationErrors']['institutionName'] = false;
                    $validationResponse['feedback']['institutionName'] = '<div class="invalid-feedback">Required</div>';
                }
            } else if ($formData['institutionListed'] == 'Yes') {
                if (!isset($formData['institution']) || empty($formData['institution'])) {
                    $validationResponse['validationErrors']['institution'] = false;
                    $validationResponse['feedback']['institution'] = '<div class="invalid-feedback">Required</div>';

                }
            }

        } else if ($formData['locatedUsa'] == 'No') {
            if (!isset($formData['institutionName']) || empty($formData['institutionName'])) {
                $validationResponse['validationErrors']['institutionName'] = false;
                $validationResponse['feedback']['institutionName'] = '<div class="invalid-feedback">Required</div>';
            }

            if (!isset($formData['country']) || empty($formData['country'])) {
                $validationResponse['validationErrors']['country'] = false;
                $validationResponse['feedback']['country'] = '<div class="invalid-feedback">Required</div>';
            }
        }

        // Course

        if (!isset($formData['courseSemester']) || empty($formData['courseSemester'])) {
            $validationResponse['validationErrors']['courseSemester'] = false;
            $validationResponse['feedback']['courseSemester'] = '<div class="invalid-feedback">Required</div>';
        }

        if (!isset($formData['courseCreditBasis']) || empty($formData['courseCreditBasis'])) {
            $validationResponse['validationErrors']['courseCreditBasis'] = false;
            $validationResponse['feedback']['courseCreditBasis'] = '<div class="invalid-feedback">Required</div>';
        }

        if (!isset($formData['courseCreditHours']) || empty($formData['courseCreditHours'])) {
            $validationResponse['validationErrors']['courseCreditHours'] = false;
            $validationResponse['feedback']['courseCreditHours'] = '<div class="invalid-feedback">Required</div>';
        }

        // Lab

        if ($formData['hasLab'] == 'Yes') {
            if (!isset($formData['labSemester']) || empty($formData['labSemester'])) {
                $validationResponse['validationErrors']['labSemester'] = false;
                $validationResponse['feedback']['labSemester'] = '<div class="invalid-feedback">Required</div>';
            }

            if (!isset($formData['labCreditBasis']) || empty($formData['labCreditBasis'])) {
                $validationResponse['validationErrors']['labCreditBasis'] = false;
                $validationResponse['feedback']['labCreditBasis'] = '<div class="invalid-feedback">Required</div>';
            }

            if (!isset($formData['labCreditHours']) || empty($formData['labCreditHours'])) {
                $validationResponse['validationErrors']['labCreditHours'] = false;
                $validationResponse['feedback']['labCreditHours'] = '<div class="invalid-feedback">Required</div>';
            }
        }

        // Course Syllabus

        if (!isset($formData['courseSyllabus']) || empty($formData['courseSyllabus'])) {
            $validationResponse['validationErrors']['courseSyllabus'] = false;
            $validationResponse['feedback']['courseSyllabus'] = '<div class="invalid-feedback">Required</div>';
        }

        // Lab Syllabus

        if ($formData['hasLab'] == 'Yes') {
            if (!isset($formData['labSyllabus']) || empty($formData['labSyllabus'])) {
                $validationResponse['validationErrors']['labSyllabus'] = false;
                $validationResponse['feedback']['labSyllabus'] = '<div class="invalid-feedback">Required</div>';
            }
        }

        $validationResponse['feedback']['example'] = '<div class="valid-feedback">Awesome</div>';

        return $validationResponse;
    }
}