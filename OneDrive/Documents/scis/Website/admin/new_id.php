<?php include 'auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zamboanga City OSCA Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body class="inter-body text-gray-700 flex flex-col min-h-screen">
    <div id="loading-overlay" class="loading-overlay hidden">
        <div class="spinner"></div>
    </div>

    <header class="bg-white border-b border-gray-200 py-3 px-4 md:px-8 flex justify-between items-center sticky top-0 z-20">
        <div class="flex items-center gap-4">
            <a href="senior_citizen_list.php" class="text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-full h-8 w-8 flex items-center justify-center transition">
                <i class="fa-solid fa-chevron-left text-sm"></i>
            </a>
            <div>
                <h1 id="form-page-title" class="font-bold text-sm md:text-base leading-tight text-black">New Senior Citizen Registration</h1>
                <p class="text-[11px] md:text-xs text-gray-500 font-medium">Office of Senior Citizens Affairs</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block leading-tight">
                <p class="text-sm font-bold text-black">Space1000</p>
                <p class="text-[10px] text-gray-500 font-semibold uppercase">Social Worker Coordinator</p>
            </div>
            <div class="h-9 w-9 rounded-full border border-gray-300 flex items-center justify-center text-gray-800 text-lg">
                <i class="fa-regular fa-user text-xl"></i>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto p-4 md:p-8 w-full flex-grow fade-in">
        <p id="progress-text" class="text-center text-[10px] font-medium text-gray-500 mb-3 uppercase tracking-wide">Step 1 of 4</p>
        <div class="w-full bg-gray-200 h-1.5 rounded-full mb-8 relative overflow-hidden">
            <div id="progress-bar" class="brand-blue h-full absolute top-0 left-0 rounded-full transition-all duration-500 ease-out" style="width: 25%"></div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8" id="stepper-header">
            <div id="tab-1" class="step-active rounded-lg py-3 px-2 text-center flex flex-col items-center justify-center shadow-md relative step-indicator cursor-pointer" onclick="goToStep(1)">
                <i class="fa-solid fa-user text-xl mb-1"></i>
                <span class="font-semibold text-xs">Personal Details</span>
            </div>
            <div id="tab-2" class="step-inactive rounded-lg py-3 px-2 text-center flex flex-col items-center justify-center shadow-md relative cursor-pointer" onclick="goToStep(2)">
                <i class="fa-solid fa-address-card text-xl mb-1"></i>
                <span class="font-semibold text-xs">Contact Information</span>
            </div>
            <div id="tab-3" class="step-inactive rounded-lg py-3 px-2 text-center flex flex-col items-center justify-center shadow-md relative cursor-pointer" onclick="goToStep(3)">
                <i class="fa-solid fa-graduation-cap text-xl mb-1"></i>
                <span class="font-semibold text-xs">Socioeconomic</span>
            </div>
            <div id="tab-4" class="step-inactive rounded-lg py-3 px-2 text-center flex flex-col items-center justify-center shadow-md relative cursor-pointer" onclick="goToStep(4)">
                <i class="fa-solid fa-file-alt text-xl mb-1"></i>
                <span class="font-semibold text-xs">Review & Submit</span>
            </div>
        </div>

        <form id="senior-registration-form">
            <input type="hidden" id="senior-id" name="id">
            <input type="hidden" id="contact-id" name="contact_id">

            <!-- Step 1: Personal Details -->
            <section id="step-1" class="step-content bg-white border border-gray-300 rounded-lg shadow-sm p-6 mb-6">
                <h3 class="font-bold brand-blue-text text-lg mb-4">Personal Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-5 gap-x-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" id="first_name" name="first_name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm" required>
                    </div>
                    <div>
                        <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                        <input type="text" id="middle_name" name="middle_name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" id="last_name" name="last_name" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm" required>
                    </div>
                    <div>
                        <label for="extension" class="block text-sm font-medium text-gray-700 mb-1">Extension</label>
                        <input type="text" id="extension" name="extension" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                    </div>
                    <div>
                        <label for="birthdate" class="block text-sm font-medium text-gray-700 mb-1">Birthdate</label>
                        <input type="date" id="birthdate" name="birthdate" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm" required>
                    </div>
                    <div>
                        <label for="gender_id" class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                        <select id="gender_id" name="gender_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm" required>
                            <!-- Options populated by JS -->
                        </select>
                    </div>
                </div>
            </section>

            <!-- Step 2: Contact Information -->
            <section id="step-2" class="step-content bg-white border border-gray-300 rounded-lg shadow-sm p-6 mb-6 hidden">
                <h3 class="font-bold brand-blue-text text-lg mb-4">Contact Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-5 gap-x-6">
                    <div>
                        <label for="mobile_number" class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
                        <input type="text" id="mobile_number" name="contact[mobile_number]" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                    </div>
                    <div>
                        <label for="telephone_number" class="block text-sm font-medium text-gray-700 mb-1">Telephone Number</label>
                        <input type="text" id="telephone_number" name="contact[telephone_number]" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="contact[email]" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                    </div>
                    <div>
                        <label for="house_number" class="block text-sm font-medium text-gray-700 mb-1">House Number</label>
                        <input type="text" id="house_number" name="contact[house_number]" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                    </div>
                    <div>
                        <label for="street" class="block text-sm font-medium text-gray-700 mb-1">Street</label>
                        <input type="text" id="street" name="contact[street]" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label for="barangay_id" class="block text-sm font-medium text-gray-700 mb-1">Barangay</label>
                        <select id="barangay_id" name="barangay_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm" required>
                            <!-- Options populated by JS -->
                        </select>
                    </div>
                </div>
            </section>

            <!-- Step 3: Socioeconomic Details -->
            <section id="step-3" class="step-content bg-white border border-gray-300 rounded-lg shadow-sm p-6 mb-6 hidden">
                <h3 class="font-bold brand-blue-text text-lg mb-4">Socioeconomic Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-5 gap-x-6">
                    <div>
                        <label for="educational_attainment_id" class="block text-sm font-medium text-gray-700 mb-1">Educational Attainment</label>
                        <select id="educational_attainment_id" name="educational_attainment_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                            <!-- Options populated by JS -->
                        </select>
                    </div>
                    <div>
                        <label for="monthly_salary" class="block text-sm font-medium text-gray-700 mb-1">Monthly Salary</label>
                        <input type="number" step="0.01" id="monthly_salary" name="monthly_salary" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                    </div>
                    <div>
                        <label for="occupation" class="block text-sm font-medium text-gray-700 mb-1">Occupation</label>
                        <input type="text" id="occupation" name="occupation" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                    </div>
                    <div>
                        <label for="other_skills" class="block text-sm font-medium text-gray-700 mb-1">Other Skills</label>
                        <input type="text" id="other_skills" name="other_skills" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                    </div>
                    <div>
                        <label for="socioeconomic_status_id" class="block text-sm font-medium text-gray-700 mb-1">Socioeconomic Status</label>
                        <select id="socioeconomic_status_id" name="socioeconomic_status_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                            <!-- Options populated by JS -->
                        </select>
                    </div>
                    <div>
                        <label for="mobility_level_id" class="block text-sm font-medium text-gray-700 mb-1">Mobility Level</label>
                        <select id="mobility_level_id" name="mobility_level_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-brandBlue focus:border-brandBlue text-sm">
                            <!-- Options populated by JS -->
                        </select>
                    </div>
                </div>
            </section>

            <!-- Step 4: Review & Submit -->
            <section id="step-4" class="step-content bg-white border border-gray-300 rounded-lg shadow-sm p-6 mb-6 hidden">
                <h3 class="font-bold brand-blue-text text-lg mb-4">Review & Confirm</h3>
                <div id="review-content" class="text-sm text-gray-700 space-y-2">
                    <!-- Review details will be populated by JS -->
                </div>
            </section>

            <div class="flex justify-between mt-8">
                <button type="button" id="prev-step-btn" class="bg-gray-300 text-gray-800 px-6 py-2 rounded-md font-semibold text-sm hover:bg-gray-400 transition hidden">Previous</button>
                <button type="button" id="next-step-btn" class="bg-brandBlue text-white px-6 py-2 rounded-md font-semibold text-sm hover:bg-blue-800 transition">Next</button>
                <button type="submit" id="submit-btn" class="bg-green-600 text-white px-6 py-2 rounded-md font-semibold text-sm hover:bg-green-700 transition hidden">Register Senior</button>
            </div>
            <div id="form-error-message" class="text-red-500 text-sm mt-4"></div>
        </form>
    </main>

    <script>
        let currentStep = 1;
        const totalSteps = 4;
        const form = document.getElementById('senior-registration-form');
        const seniorIdInput = document.getElementById('senior-id');
        const contactIdInput = document.getElementById('contact-id');
        const formPageTitle = document.getElementById('form-page-title');
        const loadingOverlay = document.getElementById('loading-overlay');
        const formErrorMessage = document.getElementById('form-error-message');

        const genderSelect = document.getElementById('gender_id');
        const educationalAttainmentSelect = document.getElementById('educational_attainment_id');
        const socioeconomicStatusSelect = document.getElementById('socioeconomic_status_id');
        const mobilityLevelSelect = document.getElementById('mobility_level_id');
        const barangaySelect = document.getElementById('barangay_id');

        document.addEventListener('DOMContentLoaded', async function() {
            loadingOverlay.classList.remove('hidden'); // Show spinner on page load

            // Fetch data for dropdowns
            await Promise.all([
                fetchAndPopulateDropdown('../api/genders/list.php', genderSelect, 'Select Gender'),
                fetchAndPopulateDropdown('../api/educational_attainment/list.php', educationalAttainmentSelect, 'Select Educational Attainment'),
                fetchAndPopulateDropdown('../api/socioeconomic_statuses/list.php', socioeconomicStatusSelect, 'Select Socioeconomic Status'),
                fetchAndPopulateDropdown('../api/mobility_levels/list.php', mobilityLevelSelect, 'Select Mobility Level'),
                fetchAndPopulateDropdown('../api/barangays/list.php', barangaySelect, 'Select Barangay')
            ]).catch(error => {
                showMessage('error', 'Fetch Error', "Failed to load form data. Please try again.");
                console.error("Error fetching dropdown data:", error);
            });

            const urlParams = new URLSearchParams(window.location.search);
            const seniorId = urlParams.get('id');

            if (seniorId) {
                formPageTitle.textContent = 'Edit Senior Citizen';
                seniorIdInput.value = seniorId;
                await fetchSeniorDetails(seniorId);
            } else {
                formPageTitle.textContent = 'New Senior Citizen Registration';
            }
            
            loadingOverlay.classList.add('hidden'); // Hide spinner after initial data load

            showStep(currentStep);
        });

        async function fetchAndPopulateDropdown(apiEndpoint, selectElement, defaultOptionText) {
            const response = await fetch(apiEndpoint);
            const data = await response.json();
            if (data.success) {
                selectElement.innerHTML = `<option value="">${defaultOptionText}</option>`;
                data.data.forEach(item => {
                    selectElement.innerHTML += `<option value="${item.id}">${item.name || item.level || item.category}</option>`;
                });
            } else {
                showMessage('error', 'Fetch Error', `Error fetching data from ${apiEndpoint}: ${data.message}`);
                console.error(`Error fetching data from ${apiEndpoint}:`, data.message);
            }
        }

        async function fetchSeniorDetails(id) {
            loadingOverlay.classList.remove('hidden');
            try {
                const response = await fetch(`../api/seniors/details.php?id=${id}`);
                const data = await response.json();
                if (data.success) {
                    const senior = data.data;
                    document.getElementById('first_name').value = senior.first_name;
                    document.getElementById('middle_name').value = senior.middle_name;
                    document.getElementById('last_name').value = senior.last_name;
                    document.getElementById('extension').value = senior.extension;
                    document.getElementById('birthdate').value = senior.birthdate;
                    document.getElementById('gender_id').value = senior.gender_id;

                    // Contact info
                    if (senior.contact_id) {
                        contactIdInput.value = senior.contact_id;
                        document.getElementById('mobile_number').value = senior.mobile_number;
                        document.getElementById('telephone_number').value = senior.telephone_number;
                        document.getElementById('email').value = senior.email;
                        document.getElementById('house_number').value = senior.house_number;
                        document.getElementById('street').value = senior.street;
                    }
                    document.getElementById('barangay_id').value = senior.barangay_id;

                    // Socioeconomic
                    document.getElementById('educational_attainment_id').value = senior.educational_attainment_id;
                    document.getElementById('monthly_salary').value = senior.monthly_salary;
                    document.getElementById('occupation').value = senior.occupation;
                    document.getElementById('other_skills').value = senior.other_skills;
                    document.getElementById('socioeconomic_status_id').value = senior.socioeconomic_status_id;
                    document.getElementById('mobility_level_id').value = senior.mobility_level_id;

                } else {
                    showMessage('error', 'Fetch Error', data.message);
                }
            } catch (error) {
                showMessage('error', 'Fetch Error', 'An unexpected error occurred while fetching details.');
                console.error('Error:', error);
            } finally {
                loadingOverlay.classList.add('hidden');
            }
        }

        function showStep(stepNum) {
            document.querySelectorAll('.step-content').forEach((section, index) => {
                section.classList.toggle('hidden', index + 1 !== stepNum);
            });
            document.querySelectorAll('#stepper-header > div').forEach((tab, index) => {
                tab.classList.remove('step-active', 'step-inactive');
                tab.classList.add(index + 1 <= stepNum ? 'step-active' : 'step-inactive');
                if (index + 1 < stepNum) {
                    tab.classList.add('step-completed'); // Optional: Add a class for completed steps
                } else {
                    tab.classList.remove('step-completed');
                }
            });

            document.getElementById('progress-text').textContent = `Step ${stepNum} of ${totalSteps}`;
            document.getElementById('progress-bar').style.width = `${(stepNum / totalSteps) * 100}%`;

            document.getElementById('prev-step-btn').classList.toggle('hidden', stepNum === 1);
            document.getElementById('next-step-btn').classList.toggle('hidden', stepNum === totalSteps);
            document.getElementById('submit-btn').classList.toggle('hidden', stepNum !== totalSteps);

            if (stepNum === totalSteps) {
                populateReviewContent();
            }
        }

        function validateStep(stepNum) {
            let isValid = true;
            const currentStepSection = document.getElementById(`step-${stepNum}`);
            const inputs = currentStepSection.querySelectorAll('[required]');
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('border-red-500'); // Highlight empty required fields
                } else {
                    input.classList.remove('border-red-500');
                }
            });
            return isValid;
        }

        function goToStep(stepNum) {
            if (stepNum < currentStep && validateStep(currentStep)) {
                currentStep = stepNum;
                showStep(currentStep);
            } else if (stepNum > currentStep && validateStep(currentStep)) {
                currentStep = stepNum;
                showStep(currentStep);
            }
        }


        document.getElementById('next-step-btn').addEventListener('click', function() {
            if (validateStep(currentStep)) {
                currentStep++;
                if (currentStep > totalSteps) currentStep = totalSteps;
                showStep(currentStep);
            } else {
                showMessage('error', 'Validation Error', "Please fill in all required fields.");
            }
        });

        document.getElementById('prev-step-btn').addEventListener('click', function() {
            currentStep--;
            if (currentStep < 1) currentStep = 1;
            showStep(currentStep);
            formErrorMessage.textContent = "";
        });

        function populateReviewContent() {
            const reviewContent = document.getElementById('review-content');
            const formData = new FormData(form);
            let html = '<h3>Personal Details</h3><ul>';
            
            html += `<li><strong>First Name:</strong> ${formData.get('first_name')}</li>`;
            html += `<li><strong>Middle Name:</strong> ${formData.get('middle_name') || 'N/A'}</li>`;
            html += `<li><strong>Last Name:</strong> ${formData.get('last_name')}</li>`;
            html += `<li><strong>Extension:</strong> ${formData.get('extension') || 'N/A'}</li>`;
            html += `<li><strong>Birthdate:</strong> ${formData.get('birthdate')}</li>`;
            html += `<li><strong>Gender:</strong> ${genderSelect.options[genderSelect.selectedIndex].text}</li>`;
            html += '</ul><h3>Contact Information</h3><ul>';
            html += `<li><strong>Mobile Number:</strong> ${formData.get('contact[mobile_number]') || 'N/A'}</li>`;
            html += `<li><strong>Telephone Number:</strong> ${formData.get('contact[telephone_number]') || 'N/A'}</li>`;
            html += `<li><strong>Email:</strong> ${formData.get('contact[email]') || 'N/A'}</li>`;
            html += `<li><strong>House Number:</strong> ${formData.get('contact[house_number]') || 'N/A'}</li>`;
            html += `<li><strong>Street:</strong> ${formData.get('contact[street]') || 'N/A'}</li>`;
            html += `<li><strong>Barangay:</strong> ${barangaySelect.options[barangaySelect.selectedIndex].text}</li>`;
            html += '</ul><h3>Socioeconomic Details</h3><ul>';
            html += `<li><strong>Educational Attainment:</strong> ${educationalAttainmentSelect.options[educationalAttainmentSelect.selectedIndex].text || 'N/A'}</li>`;
            html += `<li><strong>Monthly Salary:</strong> ${formData.get('monthly_salary') || 'N/A'}</li>`;
            html += `<li><strong>Occupation:</strong> ${formData.get('occupation') || 'N/A'}</li>`;
            html += `<li><strong>Other Skills:</strong> ${formData.get('other_skills') || 'N/A'}</li>`;
            html += `<li><strong>Socioeconomic Status:</strong> ${socioeconomicStatusSelect.options[socioeconomicStatusSelect.selectedIndex].text || 'N/A'}</li>`;
            html += `<li><strong>Mobility Level:</strong> ${mobilityLevelSelect.options[mobilityLevelSelect.selectedIndex].text || 'N/A'}</li>`;
            html += '</ul>';
            reviewContent.innerHTML = html;
        }


        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            formErrorMessage.textContent = "";

            if (!validateStep(totalSteps)) {
                showMessage('error', 'Validation Error', "Please fill in all required fields in the review step.");
                return;
            }

            loadingOverlay.classList.remove('hidden'); // Show spinner

            const formData = new FormData(form);
            const data = {};
            for (let [key, value] of formData.entries()) {
                // Handle nested contact object
                if (key.startsWith('contact[')) {
                    const contactKey = key.match(/contact\[(.*?)\]/)[1];
                    if (!data.contact) {
                        data.contact = {};
                    }
                    data.contact[contactKey] = value;
                } else {
                    data[key] = value;
                }
            }

            try {
                const response = await fetch('../api/seniors/save.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                if (result.success) {
                    showMessage('success', 'Success', 'Senior citizen registered successfully!', 'senior_citizen_list.php');
                } else {
                    showMessage('error', 'Save Error', result.message);
                }
            } catch (error) {
                showMessage('error', 'Save Error', 'An unexpected error occurred.');
                console.error('Error:', error);
            } finally {
                loadingOverlay.classList.add('hidden'); // Hide spinner
            }
        });
    </script>
</body>
</html>