<?php
    class Validator {
        
        /**
        * Validate senior citizen data
        * @param object $data
        * @return array ['valid' => bool, 'errors' => array]
        */
        public static function validateSeniorData($data) {
            $errors = [];
            
            // Required fields
            $required = [
                'first_name' => 'First name',
                'last_name' => 'Last name',
                'birthdate' => 'Birthdate',
                'gender_id' => 'Gender',
                'barangay_id' => 'Barangay'
            ];
            
            foreach ($required as $field => $label) {
                if (!isset($data->$field) || empty($data->$field)) {
                    $errors[] = "$label is required";
                }
            }
            
            // Validate birthdate format
            if (isset($data->birthdate)) {
                $date = DateTime::createFromFormat('Y-m-d', $data->birthdate);
                if (!$date || $date->format('Y-m-d') !== $data->birthdate) {
                    $errors[] = "Invalid birthdate format. Use YYYY-MM-DD";
                }
            }
            
            // Validate age requirement
            if (isset($data->birthdate)) {
                if (!AgeCalculator::isSeniorCitizen($data->birthdate)) {
                    $age = AgeCalculator::calculateAge($data->birthdate);
                    $errors[] = "Applicant must be at least 60 years old (current age: $age)";
                }
            }
            
            // Validate mobile number format
            if (isset($data->mobile_number) && !empty($data->mobile_number)) {
                if (!preg_match('/^09\d{9}$/', $data->mobile_number)) {
                    $errors[] = "Invalid mobile number format. Use 09XXXXXXXXX";
                }
            }
            
            // Validate email
            if (isset($data->email) && !empty($data->email)) {
                if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Invalid email address";
                }
            }
            
            return [
                'valid' => empty($errors),
                'errors' => $errors
            ];
        }
        
        /**
        * Sanitize input data
        * @param string $data
        * @return string
        */
        public static function sanitize($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
    }