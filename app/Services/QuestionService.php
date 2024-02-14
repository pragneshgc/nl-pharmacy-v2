<?php

namespace App\Services;

use App\Models\Questionnaire;

class QuestionService
{
    public function importFromArray(int $id, array $data): array
    {
        $errors = [];

        if (!is_array($data['Question'])) {
            $questions[] = $data['Question'];
        } else {
            $questions = $data['Question'];
        }

        if (!is_array($data['Answer'])) {
            $answers[] = $data['Answer'];
        } else {
            $answers = $data['Answer'];
        }

        if (!empty($answers) && !empty($questions)) {
            Questionnaire::where('PrescriptionID', $id)->delete();

            $insert = [];
            if (!empty($questions)) {
                for ($i = 0; $i < count($questions); $i++) {
                    $insert[] = [
                        'PrescriptionID' => $id,
                        'Question' => $questions[$i],
                        'Answer' => !empty($answers[$i]) ? $answers[$i] : '',
                        'Status' => 1,
                    ];
                }
                if (!empty($insert)) {
                    Questionnaire::insert($insert);
                }
            }
        } else {
            $errors[] = 'Questionnaire not found';
        }

        return $errors;
    }
}
