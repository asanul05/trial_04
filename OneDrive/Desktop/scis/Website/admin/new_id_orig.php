<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zamboanga City OSCA Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="inter-body text-gray-700 flex flex-col min-h-screen">

    <header class="bg-white border-b border-gray-200 py-3 px-4 md:px-8 flex justify-between items-center sticky top-0 z-20">
        <div class="flex items-center gap-4">
            <a href="registration_category.php" onclick="goBack()" class="text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-full h-8 w-8 flex items-center justify-center transition">
                <i class="fa-solid fa-chevron-left text-sm"></i>
            </a>
            <div>
                <h1 class="font-bold text-sm md:text-base leading-tight text-black">Zamboanga City OSCA</h1>
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

    <main class="max-w-6xl mx-auto p-4 md:p-8 w-full flex-grow">
        
        <p id="progress-text" class="text-center text-[10px] font-medium text-gray-500 mb-3 uppercase tracking-wide">Step 1 of 4</p>
        
        <div class="w-full bg-gray-200 h-1.5 rounded-full mb-8 relative overflow-hidden">
            <div id="progress-bar" class="brand-blue h-full absolute top-0 left-0 rounded-full transition-all duration-500 ease-out" style="width: 25%"></div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div id="tab-1" class="step-active rounded-lg py-3 px-2 text-center flex flex-col items-center justify-center shadow-md relative step-indicator cursor-pointer" onclick="goToStep(1)">
                <div class="w-5 h-5 rounded-full bg-white brand-blue-text font-bold text-[10px] flex items-center justify-center mb-1">1</div>
                <span class="text-[11px] md:text-xs font-bold leading-tight">Personal Information</span>
            </div>
            <div id="tab-2" class="step-inactive rounded-lg py-3 px-2 text-center flex flex-col items-center justify-center cursor-default">
                <div class="w-5 h-5 rounded-full bg-gray-400 text-white font-bold text-[10px] flex items-center justify-center mb-1">2</div>
                <span class="text-[11px] md:text-xs font-bold leading-tight">Family Composition</span>
            </div>
            <div id="tab-3" class="step-inactive rounded-lg py-3 px-2 text-center flex flex-col items-center justify-center cursor-default">
                <div class="w-5 h-5 rounded-full bg-gray-400 text-white font-bold text-[10px] flex items-center justify-center mb-1">3</div>
                <span class="text-[11px] md:text-xs font-bold leading-tight">Association</span>
            </div>
            <div id="tab-4" class="step-inactive rounded-lg py-3 px-2 text-center flex flex-col items-center justify-center cursor-default">
                <div class="w-5 h-5 rounded-full bg-gray-400 text-white font-bold text-[10px] flex items-center justify-center mb-1">4</div>
                <span class="text-[11px] md:text-xs font-bold leading-tight">Requirements</span>
            </div>
        </div>

        <div id="step-1-content" class="fade-in">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-10">
                
                <div class="flex flex-col md:flex-row justify-between md:items-center mb-8 gap-4">
                    <h2 class="text-xl font-bold brand-blue-text">Personal Na Impormasyon</h2>
                    <input class="border border-gray-300 text-gray-500 text-sm px-6 py-2 rounded-md bg-white hover:bg-gray-50 w-full md:w-auto text-center" placeholder="Input Employee Name">
                </div>

                <form>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5">
                        <div><label class="block text-sm font-semibold mb-2 text-black">First Name <span class="text-orange-500">*</span></label><input type="text" class="w-full border border-gray-300 rounded-md p-3 text-gray-700"></div>
                        <div><label class="block text-sm font-semibold mb-2 text-black">Middle Name/Middle Initial <span class="text-orange-500">*</span></label><input type="text" class="w-full border border-gray-300 rounded-md p-3 text-gray-700"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-5">
                        <div class="md:col-span-2"><label class="block text-sm font-semibold mb-2 text-black">Last Name <span class="text-orange-500">*</span></label><input type="text" class="w-full border border-gray-300 rounded-md p-3 text-gray-700"></div>
                        <div><label class="block text-sm font-semibold mb-2 text-black">Extension</label><input type="text" class="w-full border border-gray-300 rounded-md p-3 text-gray-700"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-5">
                         <div><label class="block text-sm font-semibold mb-2 text-black">House No. <span class="text-orange-500">*</span></label><input type="text" class="w-full border border-gray-300 rounded-md p-3 text-gray-700"></div>
                         <div class="md:col-span-2"><label class="block text-sm font-semibold mb-2 text-black">Street <span class="text-orange-500">*</span></label><input type="text" class="w-full border border-gray-300 rounded-md p-3 text-gray-700"></div>
                    </div>
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5">
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-black">Barangay <span class="text-orange-500">*</span></label>
                            <div class="relative">
                                <select class="w-full border border-gray-300 rounded-md p-3 appearance-none text-gray-400 bg-white"><option>Select Barangay</option></select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-black"><i class="fa-solid fa-chevron-down text-xs"></i></div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-black">City, Province, Postal Code</label>
                            <div class="relative">
                                <input type="text" value="Zamboanga City, Philippines, 7000" disabled class="w-full border border-gray-300 rounded-md p-3 bg-gray-100 text-black font-medium pr-10">
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-600"><i class="fa-solid fa-location-dot"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-10 gap-6 mb-5">
                        <div class="md:col-span-4">
                            <label class="block text-sm font-semibold mb-2 text-black">Date of Birth <span class="text-orange-500">*</span></label>
                            <div class="relative">
                                <input type="date" placeholder="dd / mm / yy" class="w-full border border-gray-300 rounded-md p-3 text-gray-500">
                                <div class="absolute inset-y-0 right-0 flex items-center px-3 text-black text-lg"></div>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold mb-2 text-black">Age <span class="text-orange-500">*</span></label>
                            <input type="text" class="w-full border border-gray-300 rounded-md p-3 text-gray-700">
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-sm font-semibold mb-3 text-black">Sex/Gender <span class="text-orange-500">*</span></label>
                            <div class="flex items-center gap-6 mt-3">
                                <label class="flex items-center cursor-pointer"><input type="radio" name="gender" class="w-5 h-5 text-blue-900 border-gray-300"><span class="ml-2 text-sm font-bold text-black">Lalaki/Male</span></label>
                                <label class="flex items-center cursor-pointer"><input type="radio" name="gender" class="w-5 h-5 text-blue-900 border-gray-300"><span class="ml-2 text-sm font-bold text-black">Babae/Female</span></label>
                            </div>
                        </div>
                    </div>

                    <h2 class="text-xl font-bold brand-blue-text mb-6 pt-4 border-t border-gray-100">Contact & Background Information</h2>
                    <div class="mb-5">
                        <label class="block text-sm font-semibold mb-2 text-black">Educational Attainment <span class="text-orange-500">*</span></label>
                        <div class="relative">
                            <select class="w-full border border-gray-300 rounded-md p-3 appearance-none text-gray-400 bg-white"><option>Select educational level</option></select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-black"><i class="fa-solid fa-chevron-down text-xs"></i></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5">
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-black">Mobile Number</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 text-lg"><i class="fa-solid fa-mobile-screen"></i></div>
                                <input type="text" placeholder="09xxx-xxxx-xxxx" class="w-full border border-gray-300 rounded-md p-3 pl-12 text-gray-700">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-black">Telephone Number</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500"><i class="fa-solid fa-phone"></i></div>
                                <input type="text" placeholder="(062)-xxxx-xxxx" class="w-full border border-gray-300 rounded-md p-3 pl-12 text-gray-700">
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5">
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-black">Monthly Salary</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 font-bold text-lg">₱</div>
                                <input type="text" placeholder="0.00" class="w-full border border-gray-300 rounded-md p-3 pl-10 text-gray-700">
                            </div>
                        </div>
                        <div><label class="block text-sm font-semibold mb-2 text-black">Occupation</label><input type="text" class="w-full border border-gray-300 rounded-md p-3 text-gray-700"></div>
                    </div>
                    <div class="mb-12">
                        <label class="block text-sm font-semibold mb-2 text-black">Other skills</label>
                        <input type="text" class="w-full border border-gray-300 rounded-md p-3 text-gray-700 pb-10">
                    </div>

                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 pt-6 border-t border-gray-200">
                        <div class="flex gap-4 w-full md:w-auto">
                            <button type="button" class="w-full md:w-auto px-6 py-3 border border-gray-400 rounded-lg text-black font-medium hover:bg-gray-50">Back</button>
                            <button type="button" class="w-full md:w-auto px-6 py-3 border border-gray-400 rounded-lg text-black font-medium hover:bg-gray-50">Reset Form</button>
                        </div>
                        <div class="flex gap-4 w-full md:w-auto">
                            <button type="button" class="w-full md:w-auto px-6 py-3 border border-gray-400 rounded-lg text-black font-medium hover:bg-gray-50 flex items-center justify-center gap-2">
                                <i class="fa-regular fa-floppy-disk"></i> Save Draft
                            </button>
                            <button type="button" onclick="goToStep(2)" class="w-full md:w-auto px-6 py-3 brand-blue text-white font-medium rounded-lg hover:opacity-90 flex items-center justify-center gap-2 transition">
                                Next Step: Family <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="step-2-content" class="hidden fade-in">
            <div class="mb-4">
                <p class="text-xs text-gray-600 mb-3">To Add a Family Member Please click Add Person Button</p>
                <div class="flex gap-3">
                    <button onclick="addFamilyMember()" class="bg-green-500 hover:bg-green-600 text-white text-sm font-bold py-2 px-6 rounded shadow transition flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i> Add Person
                    </button>
                    <button onclick="deleteSelected()" class="bg-red-500 hover:bg-red-600 text-white text-sm font-bold py-2 px-6 rounded shadow transition flex items-center gap-2">
                        <i class="fa-solid fa-trash-can"></i> Delete Selected
                    </button>
                </div>
            </div>

            <div id="family-container" class="space-y-6"></div>

            <div class="flex flex-col md:flex-row justify-between items-center gap-4 pt-10 mt-6 border-t border-gray-200">
                <div class="flex gap-4 w-full md:w-auto">
                    <button class="w-full md:w-auto px-6 py-3 border border-gray-400 rounded-lg text-black font-medium hover:bg-gray-50 text-lg">Cancel</button>
                    <button onclick="document.getElementById('family-container').innerHTML = ''" class="w-full md:w-auto px-6 py-3 border border-gray-400 rounded-lg text-black font-medium hover:bg-gray-50 text-lg">Reset Form</button>
                </div>
                
                <div class="flex gap-4 w-full md:w-auto">
                    <button class="w-full md:w-auto px-6 py-3 border border-gray-400 rounded-lg text-black font-medium hover:bg-gray-50 flex items-center justify-center gap-2 text-lg">
                        <i class="fa-regular fa-floppy-disk"></i> Save Draft
                    </button>
                    <button onclick="goToStep(3)" class="w-full md:w-auto px-6 py-3 brand-blue text-white font-bold rounded-lg hover:opacity-90 flex items-center justify-center gap-2 transition text-lg">
                        Next Step: Association <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <div id="step-3-content" class="hidden fade-in">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-10">
                
                <h2 class="text-xl font-bold brand-blue-text mb-6">Target Sector (Pangunahing Sektor)</h2>

                <div class="bg-blue-50 text-blue-900 p-4 rounded-lg mb-8 font-medium text-sm">
                    Select ALL categories that apply to this senior citizen. Multiple selections are allowed.
                </div>

                <form>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="font-bold text-lg mb-4 text-black">Target Sectors</h3>
                            <div class="space-y-4">
                                <label class="border border-gray-300 rounded-xl p-4 flex items-start gap-4 cursor-pointer hover:border-blue-800 transition">
                                    <input type="checkbox" class="checkbox-lg mt-1 accent-blue-900">
                                    <div><p class="font-bold text-sm text-black">PNGNA</p><p class="text-xs text-gray-500 mt-1">Member of national senior citizens organization</p></div>
                                </label>
                                <label class="border border-gray-300 rounded-xl p-4 flex items-start gap-4 cursor-pointer hover:border-blue-800 transition">
                                    <input type="checkbox" class="checkbox-lg mt-1 accent-blue-900">
                                    <div><p class="font-bold text-sm text-black">WEPC</p><p class="text-xs text-gray-500 mt-1">Female senior citizens in empowerment programs</p></div>
                                </label>
                                <label class="border border-gray-300 rounded-xl p-4 flex items-start gap-4 cursor-pointer hover:border-blue-800 transition">
                                    <input type="checkbox" class="checkbox-lg mt-1 accent-blue-900">
                                    <div><p class="font-bold text-sm text-black">PWD</p><p class="text-xs text-gray-500 mt-1">Senior with recognized disability</p></div>
                                </label>
                                <label class="border border-gray-300 rounded-xl p-4 flex items-start gap-4 cursor-pointer hover:border-blue-800 transition">
                                    <input type="checkbox" class="checkbox-lg mt-1 accent-blue-900">
                                    <div><p class="font-bold text-sm text-black">YNSP</p><p class="text-xs text-gray-500 mt-1">Special care program</p></div>
                                </label>
                                <label class="border border-gray-300 rounded-xl p-4 flex items-start gap-4 cursor-pointer hover:border-blue-800 transition">
                                    <input type="checkbox" class="checkbox-lg mt-1 accent-blue-900">
                                    <div><p class="font-bold text-sm text-black">PASP</p><p class="text-xs text-gray-500 mt-1">Hope and support program members</p></div>
                                </label>
                                <label class="border border-gray-300 rounded-xl p-4 flex items-start gap-4 cursor-pointer hover:border-blue-800 transition">
                                    <input type="checkbox" class="checkbox-lg mt-1 accent-blue-900">
                                    <div><p class="font-bold text-sm text-black">KIA/WIA</p></div>
                                </label>
                                <label class="border border-gray-300 rounded-xl p-4 flex items-start gap-4 cursor-pointer hover:border-blue-800 transition h-auto">
                                    <input type="checkbox" class="checkbox-lg mt-1 accent-blue-900">
                                    <div class="w-full">
                                        <p class="font-bold text-sm text-black">Other</p>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-xs text-gray-500">Please specify:</span>
                                            <input type="text" class="border-b border-gray-400 focus:outline-none focus:border-blue-900 w-full text-sm">
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <h3 class="font-bold text-lg mb-4 text-black">Special Sub-Categories</h3>
                            <div class="space-y-4">
                                <label class="border border-gray-300 rounded-xl p-4 flex items-start gap-4 cursor-pointer hover:border-blue-800 transition">
                                    <input type="checkbox" class="checkbox-lg mt-1 accent-blue-900">
                                    <div><p class="font-bold text-sm text-black">Solo Parents</p><p class="text-xs text-gray-500 mt-1">Senior citizen raising children alone</p></div>
                                </label>
                                <label class="border border-gray-300 rounded-xl p-4 flex items-start gap-4 cursor-pointer hover:border-blue-800 transition">
                                    <input type="checkbox" class="checkbox-lg mt-1 accent-blue-900">
                                    <div><p class="font-bold text-sm text-black">Indigenous Person (IP)</p></div>
                                </label>
                                <label class="border border-gray-300 rounded-xl p-4 flex items-start gap-4 cursor-pointer hover:border-blue-800 transition">
                                    <input type="checkbox" class="checkbox-lg mt-1 accent-blue-900">
                                    <div><p class="font-bold text-sm text-black">Recovering Person who used drugs</p></div>
                                </label>
                                <label class="border border-gray-300 rounded-xl p-4 flex items-start gap-4 cursor-pointer hover:border-blue-800 transition">
                                    <input type="checkbox" class="checkbox-lg mt-1 accent-blue-900">
                                    <div><p class="font-bold text-sm text-black">4P's DSWD Beneficiaries</p></div>
                                </label>
                                <label class="border border-gray-300 rounded-xl p-4 flex items-start gap-4 cursor-pointer hover:border-blue-800 transition">
                                    <input type="checkbox" class="checkbox-lg mt-1 accent-blue-900">
                                    <div><p class="font-bold text-sm text-black">Street Dwellers</p></div>
                                </label>
                                <label class="border border-gray-300 rounded-xl p-4 flex items-start gap-4 cursor-pointer hover:border-blue-800 transition">
                                    <input type="checkbox" class="checkbox-lg mt-1 accent-blue-900">
                                    <div><p class="font-bold text-sm text-black">Psychosocial/Mental/Learning Disability</p></div>
                                </label>
                                <label class="border border-gray-300 rounded-xl p-4 flex items-start gap-4 cursor-pointer hover:border-blue-800 transition">
                                    <input type="checkbox" class="checkbox-lg mt-1 accent-blue-900">
                                    <div><p class="font-bold text-sm text-black">Stateless Person/Asylum</p></div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 pt-10 mt-6 border-t border-gray-200">
                        <div class="flex gap-4 w-full md:w-auto">
                            <button class="w-full md:w-auto px-6 py-3 border border-gray-400 rounded-lg text-black font-medium hover:bg-gray-50 text-lg">Cancel</button>
                            <button class="w-full md:w-auto px-6 py-3 border border-gray-400 rounded-lg text-black font-medium hover:bg-gray-50 text-lg">Reset Form</button>
                        </div>
                        
                        <div class="flex gap-4 w-full md:w-auto">
                            <button class="w-full md:w-auto px-6 py-3 border border-gray-400 rounded-lg text-black font-medium hover:bg-gray-50 flex items-center justify-center gap-2 text-lg">
                                <i class="fa-regular fa-floppy-disk"></i> Save Draft
                            </button>
                            <button type="button" onclick="goToStep(4)" class="w-full md:w-auto px-6 py-3 brand-blue text-white font-bold rounded-lg hover:opacity-90 flex items-center justify-center gap-2 transition text-lg">
                                Next Step: Requirements <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div id="step-4-content" class="hidden fade-in">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-10">
                
                <h2 class="text-xl font-bold brand-blue-text mb-4">Upload Required Documents</h2>
                <p class="text-sm text-gray-600 mb-8">Please upload clear, readable copies of the following documents (JPG, PNG, or PDF Format).<br><span class="text-xs text-gray-500">Maximum file size: 5MB per document</span></p>

                <div class="space-y-6 mb-10">
                    
                    <div class="border border-gray-300 rounded-xl p-6">
                        <h3 class="font-bold text-sm text-black mb-3">Proof of Age (Birth Certificate / ID): <span class="text-orange-500">*</span></h3>
                        <div class="flex flex-col gap-2">
                            <input type="file" class="w-full text-sm text-gray-500">
                            <p class="text-xs text-gray-500 italic mt-1">Upload birth certificate or any valid government ID showing date of birth</p>
                        </div>
                    </div>

                    <div class="border border-gray-300 rounded-xl p-6">
                        <h3 class="font-bold text-sm text-black mb-3">Barangay Certification / Residency: <span class="text-orange-500">*</span></h3>
                        <div class="flex flex-col gap-2">
                            <input type="file" class="w-full text-sm text-gray-500">
                            <p class="text-xs text-gray-500 italic mt-1">Certificate of Residency or Barangay Clearance from your barangay</p>
                        </div>
                    </div>

                    <div class="border border-gray-300 rounded-xl p-6">
                        <h3 class="font-bold text-sm text-black mb-3">Comelec ID / Certification (optional):</h3>
                        <div class="flex flex-col gap-2">
                            <input type="file" class="w-full text-sm text-gray-500">
                            <p class="text-xs text-gray-500 italic mt-1">Voter's ID or certification (if available)</p>
                        </div>
                    </div>

                    <div class="border border-gray-300 rounded-xl p-6">
                        <h3 class="font-bold text-sm text-black mb-3">Senior Picture: <span class="text-orange-500">*</span></h3>
                        <div class="flex flex-col gap-2">
                            <input type="file" class="w-full text-sm text-gray-500">
                        </div>
                    </div>

                    <div class="border border-gray-300 rounded-xl p-6">
                        <h3 class="font-bold text-sm text-black mb-4">Thumbmark</h3>
                        <div class="border border-gray-400 rounded px-4 py-3 flex items-center gap-3">
                            <input type="checkbox" class="checkbox-lg accent-blue-900">
                            <span class="text-xs text-gray-500 italic">This Certify That the Person has provided a thumb mark Information and is Verified by The Personel Incharge</span>
                        </div>
                    </div>

                </div>

                <div class="flex flex-col md:flex-row justify-between items-center gap-4 pt-10 border-t border-gray-200">
                    <div class="flex gap-4 w-full md:w-auto">
                        <button class="w-full md:w-auto px-6 py-3 border border-gray-400 rounded-lg text-black font-medium hover:bg-gray-50 text-lg">Cancel</button>
                        <button class="w-full md:w-auto px-6 py-3 border border-gray-400 rounded-lg text-black font-medium hover:bg-gray-50 text-lg">Reset Form</button>
                    </div>
                    
                    <div class="flex gap-4 w-full md:w-auto">
                        <button class="w-full md:w-auto px-6 py-3 border border-gray-400 rounded-lg text-black font-medium hover:bg-gray-50 flex items-center justify-center gap-2 text-lg">
                            <i class="fa-regular fa-floppy-disk"></i> Save Draft
                        </button>
                        <a href="registration_category.php" onclick="alert('Form Submitted to OSCA!')" class="w-full md:w-auto px-8 py-3 brand-blue text-white font-bold rounded-lg hover:opacity-90 flex items-center justify-center gap-2 transition text-lg">
                            Submit to Osca <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>

    </main>

    <script>
        // --- Navigation Logic ---
        let currentStep = 1;

        function goToStep(step) {
            const step1 = document.getElementById('step-1-content');
            const step2 = document.getElementById('step-2-content');
            const step3 = document.getElementById('step-3-content');
            const step4 = document.getElementById('step-4-content');
            
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');
            
            const tab1 = document.getElementById('tab-1');
            const tab2 = document.getElementById('tab-2');
            const tab3 = document.getElementById('tab-3');
            const tab4 = document.getElementById('tab-4');

            currentStep = step;

            // Hide all content
            [step1, step2, step3, step4].forEach(el => el.classList.add('hidden'));

            // Base styling classes
            const activeClass = "step-active rounded-lg py-3 px-2 text-center flex flex-col items-center justify-center shadow-md relative step-indicator";
            const completedClass = "bg-blue-900 text-white rounded-lg py-3 px-2 text-center flex flex-col items-center justify-center cursor-pointer opacity-90";
            const inactiveClass = "step-inactive rounded-lg py-3 px-2 text-center flex flex-col items-center justify-center cursor-default";

            if (step === 1) {
                step1.classList.remove('hidden');
                progressBar.style.width = '25%';
                progressText.innerText = 'Step 1 of 4';
                
                tab1.className = activeClass;
                tab2.className = inactiveClass;
                tab3.className = inactiveClass;
                tab4.className = inactiveClass;
            } 
            else if (step === 2) {
                step2.classList.remove('hidden');
                progressBar.style.width = '50%';
                progressText.innerText = 'Step 2 of 4';

                tab1.className = completedClass;
                tab2.className = activeClass;
                tab3.className = inactiveClass;
                tab4.className = inactiveClass;
            }
            else if (step === 3) {
                step3.classList.remove('hidden');
                progressBar.style.width = '75%';
                progressText.innerText = 'Step 3 of 4';

                tab1.className = completedClass;
                tab2.className = completedClass;
                tab3.className = activeClass;
                tab4.className = inactiveClass;
            }
            else if (step === 4) {
                step4.classList.remove('hidden');
                progressBar.style.width = '100%';
                progressText.innerText = 'Step 4 of 4';

                tab1.className = completedClass;
                tab2.className = completedClass;
                tab3.className = completedClass;
                tab4.className = activeClass;
            }
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function goBack() {
            if(currentStep > 1) {
                goToStep(currentStep - 1);
            }
        }

        // --- Step 2: Family Logic ---
        const container = document.getElementById('family-container');

        function createMemberHTML(index) {
            return `
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:p-8 member-card relative fade-in">
                <div class="flex items-center gap-4 mb-6">
                    <div class="h-6 w-6 relative">
                        <input type="checkbox" class="member-checkbox w-6 h-6 border-2 border-gray-300 rounded cursor-pointer accent-blue-900">
                    </div>
                    <h3 class="text-xl font-medium text-black member-title">Family Member ${index}</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-5">
                    <div><label class="block text-sm font-bold mb-2 text-black">First Name <span class="text-orange-500">*</span></label><input type="text" class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 h-12"></div>
                    <div><label class="block text-sm font-bold mb-2 text-black">Middle Name/Middle Initial <span class="text-orange-500">*</span></label><input type="text" class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 h-12"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-5">
                    <div class="md:col-span-2"><label class="block text-sm font-bold mb-2 text-black">Last Name <span class="text-orange-500">*</span></label><input type="text" class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 h-12"></div>
                    <div><label class="block text-sm font-bold mb-2 text-black">Extension</label><input type="text" class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 h-12"></div>
                </div>
                 <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div><label class="block text-sm font-bold mb-2 text-black">Relationship <span class="text-orange-500">*</span></label><input type="text" class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 h-12"></div>
                    <div><label class="block text-sm font-bold mb-2 text-black">Age <span class="text-orange-500">*</span></label><input type="number" class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 h-12"></div>
                    <div><label class="block text-sm font-bold mb-2 text-black">Monthly Salary <span class="text-orange-500">*</span></label><input type="text" class="w-full border border-gray-300 rounded-lg p-3 text-gray-700 h-12"></div>
                </div>
            </div>`;
        }

        function addFamilyMember() {
            const currentCount = container.children.length + 1;
            const newMemberDiv = document.createElement('div');
            newMemberDiv.innerHTML = createMemberHTML(currentCount);
            container.appendChild(newMemberDiv.firstElementChild);
        }

        function deleteSelected() {
            const checkboxes = document.querySelectorAll('.member-checkbox:checked');
            if (checkboxes.length === 0) { alert("Please check the box next to the family member you wish to delete."); return; }
            if (confirm(`Are you sure you want to remove ${checkboxes.length} family member(s)?`)) {
                checkboxes.forEach(checkbox => { const card = checkbox.closest('.member-card'); if (card) card.remove(); });
                renumberMembers();
            }
        }

        function renumberMembers() {
            const titles = document.querySelectorAll('.member-title');
            titles.forEach((title, index) => { title.textContent = `Family Member ${index + 1}`; });
        }
    </script>
</body>
</html>