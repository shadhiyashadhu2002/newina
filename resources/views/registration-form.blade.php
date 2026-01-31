@extends('layouts.app')

@section('content')
<div style="min-height: 100vh; background: linear-gradient(135deg, #e91e63 0%, #c2185b 100%); padding: 20px;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <!-- Header -->
        <div style="background: white; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h1 style="color: #e91e63; margin: 0; font-size: 28px; font-weight: bold;">Registration Form</h1>
            <p style="color: #666; margin: 5px 0 0 0;">Fill in the details below</p>
        </div>

        <!-- Form Container -->
        <div style="background: white; border-radius: 10px; padding: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <form id="registrationForm" enctype="multipart/form-data">
                @csrf
                
                <!-- IMID Section -->
                <div style="margin-bottom: 30px; padding: 20px; background: #fce4ec; border-radius: 8px; border-left: 4px solid #e91e63;">
                    <label style="display: block; color: #e91e63; font-weight: 600; margin-bottom: 10px;">IMID (Member ID) *</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" name="imid" id="imid" required
                            style="flex: 1; padding: 12px; border: 2px solid #f8bbd0; border-radius: 8px; font-size: 16px;"
                            placeholder="Enter IMID (e.g., IMA123456)">
                        <button type="button" id="fetchBtn"
                            style="padding: 12px 30px; background: #e91e63; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s;">
                            Fetch Profile
                        </button>
                    </div>
                    <div id="fetchStatus" style="margin-top: 10px; font-size: 14px;"></div>
                </div>

                <!-- Package Information -->
                <div style="margin-bottom: 30px;">
                    <h3 style="color: #e91e63; border-bottom: 2px solid #e91e63; padding-bottom: 10px; margin-bottom: 20px;">📦 Package Information</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Package</label>
                            <input type="text" name="package" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Amount</label>
                            <input type="number" name="amount" step="0.01" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Paid On</label>
                            <input type="date" name="paidOn" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Duration</label>
                            <input type="text" name="duration" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Months</label>
                            <input type="number" name="months" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <div style="margin-bottom: 30px;">
                    <h3 style="color: #e91e63; border-bottom: 2px solid #e91e63; padding-bottom: 10px; margin-bottom: 20px;">👤 Personal Information</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">First Name *</label>
                            <input type="text" name="firstName" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Last Name</label>
                            <input type="text" name="lastName" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Email</label>
                            <input type="email" name="email" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Gender</label>
                            <select name="gender" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                                <option value="">Select Gender</option>
                                <option value="1">Male</option>
                                <option value="2">Female</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Phone</label>
                            <input type="tel" name="phone" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Date of Birth</label>
                            <input type="date" name="dateOfBirth" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Age</label>
                            <input type="number" name="age" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Star</label>
                            <input type="text" name="star" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Marital Status</label>
                            <select name="maritalStatus" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                                <option value="">Select Status</option>
                                <option value="single">Single</option>
                                <option value="married">Married</option>
                                <option value="divorced">Divorced</option>
                                <option value="widowed">Widowed</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">No of Children</label>
                            <input type="number" name="children" min="0" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Annual Salary</label>
                            <input type="text" name="annualSalary" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;" placeholder="e.g., 5-10 Lakhs">
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div style="margin-bottom: 30px;">
                    <h3 style="color: #e91e63; border-bottom: 2px solid #e91e63; padding-bottom: 10px; margin-bottom: 20px;">🏠 Address Information</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                        <div style="grid-column: 1 / -1;">
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">House Name</label>
                            <textarea name="houseName" rows="2" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;"></textarea>
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Post Office</label>
                            <input type="text" name="po" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Via</label>
                            <input type="text" name="via" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">City</label>
                            <input type="text" name="city" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                    </div>
                </div>

                <!-- Education & Career -->
                <div style="margin-bottom: 30px;">
                    <h3 style="color: #e91e63; border-bottom: 2px solid #e91e63; padding-bottom: 10px; margin-bottom: 20px;">🎓 Education & Career</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Education</label>
                            <input type="text" name="education" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Institution</label>
                            <input type="text" name="institution" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Islamic Studies</label>
                            <input type="text" name="islamicStudies" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Designation</label>
                            <input type="text" name="designation" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Company</label>
                            <input type="text" name="company" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                    </div>
                </div>

                <!-- Physical Attributes -->
                <div style="margin-bottom: 30px;">
                    <h3 style="color: #e91e63; border-bottom: 2px solid #e91e63; padding-bottom: 10px; margin-bottom: 20px;">💪 Physical Attributes</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Height (cm)</label>
                            <input type="text" name="height" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;" placeholder="e.g., 170">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Weight (kg)</label>
                            <input type="text" name="weight" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;" placeholder="e.g., 65">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Blood Group</label>
                            <select name="bloodGroup" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                                <option value="">Select Blood Group</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Body Type</label>
                            <select name="bodyType" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                                <option value="">Select Body Type</option>
                                <option value="slim">Slim</option>
                                <option value="average">Average</option>
                                <option value="athletic">Athletic</option>
                                <option value="heavy">Heavy</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Complexion</label>
                            <select name="complexion" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                                <option value="">Select Complexion</option>
                                <option value="fair">Fair</option>
                                <option value="wheatish">Wheatish</option>
                                <option value="dark">Dark</option>
                            </select>
                        </div>
                        <div style="grid-column: 1 / -1;">
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Disability (if any)</label>
                            <textarea name="disability" rows="2" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;" placeholder="Mention any disability or leave blank"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Spiritual Background -->
                <div style="margin-bottom: 30px;">
                    <h3 style="color: #e91e63; border-bottom: 2px solid #e91e63; padding-bottom: 10px; margin-bottom: 20px;">🕌 Spiritual Background</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Religion</label>
                            <input type="text" name="religion" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Caste</label>
                            <input type="text" name="caste" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Sub Caste</label>
                            <input type="text" name="subCaste" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                    </div>
                </div>

                <!-- Family Details -->
                <div style="margin-bottom: 30px;">
                    <h3 style="color: #e91e63; border-bottom: 2px solid #e91e63; padding-bottom: 10px; margin-bottom: 20px;">👨‍👩‍👧‍👦 Family Details</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Father</label>
                            <input type="text" name="father" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;" placeholder="Father's Name">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Father Occupation</label>
                            <input type="text" name="fatherOccupation" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Mother</label>
                            <input type="text" name="mother" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;" placeholder="Mother's Name">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Mother Occupation</label>
                            <input type="text" name="motherOccupation" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Brothers (Married)</label>
                            <input type="text" name="brothers" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;" placeholder="e.g., 2 (1 Married)">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Sisters (Married)</label>
                            <input type="text" name="sistersMarried" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;" placeholder="e.g., 1 (Married)">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Family Standard</label>
                            <select name="familyStandard" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                                <option value="">Select Standard</option>
                                <option value="upper_class">Upper Class</option>
                                <option value="upper_middle">Upper Middle Class</option>
                                <option value="middle_class">Middle Class</option>
                                <option value="lower_middle">Lower Middle Class</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Unmatched Stars</label>
                            <input type="text" name="unmatchedStars" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;" placeholder="Stars to avoid">
                        </div>
                    </div>
                </div>

                <!-- Partner Preferences -->
                <div style="margin-bottom: 30px;">
                    <h3 style="color: #e91e63; border-bottom: 2px solid #e91e63; padding-bottom: 10px; margin-bottom: 20px;">💑 Partner Preferences</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Preferred Age</label>
                            <input type="text" name="preferredAge" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;" placeholder="e.g., 25-30">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Preferred Height</label>
                            <input type="text" name="preferredHeight" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;" placeholder="e.g., 165-175 cm">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Preferred Education</label>
                            <input type="text" name="preferredEducation" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Preferred District</label>
                            <input type="text" name="preferredDistrict" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Preferred Family Value</label>
                            <input type="text" name="preferredFamilyValue" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                        <div>
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 5px;">Preferred Complexion</label>
                            <input type="text" name="preferredComplexion" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                    </div>
                </div>

                <!-- Welcome Call Details -->
                <div style="margin-bottom: 30px;">
                    <h3 style="color: #e91e63; border-bottom: 2px solid #e91e63; padding-bottom: 10px; margin-bottom: 20px;">📞 Welcome Call Details</h3>
                    <textarea name="welcomeCallDetails" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;" placeholder="Notes from welcome call..."></textarea>
                </div>

                <!-- Declaration Box -->
                <div style="margin-bottom: 30px;">
                    <h3 style="color: #e91e63; border-bottom: 2px solid #e91e63; padding-bottom: 10px; margin-bottom: 20px;">✍️ ഡിജിറ്റൽ സത്യപ്രസ്ഥാപനം</h3>
                    <div style="background: #fef5f8; border: 2px solid #e91e63; border-radius: 10px; padding: 25px;">
                        <p style="color: #333; line-height: 2.5; font-size: 16px; margin: 0;">
                          

ഞാൻ, ഇണ മാട്രിമോണിയുടെ എല്ലാ സർവീസ് നിബന്ധനകളും (Terms & Conditions) പൂർണ്ണമായി  മനസ്സിലാക്കിയതായും, യാതൊരു സമ്മർദ്ദവും ഇല്ലാതെ അവയെ സ്വമേധയാ അംഗീകരിക്കുന്നതായും ഇതിലൂടെ സ്ഥിരീകരിക്കുന്നു.

ഇണ മാട്രിമോണി സർവീസിനായി ഞാൻ അടച്ചിരിക്കുന്ന സർവീസ് അഡ്വാൻസ് തുക റീഫണ്ട് ലഭിക്കുന്നതല്ല (Non-Refundable) എന്ന കാര്യം എനിക്ക് വ്യക്തമായി അറിയാവുന്നതും, അതിന് ഞാൻ പൂര്‍ണ്ണ സമ്മതം നൽകുന്നതുമാണ്.

ഈ സമ്മതം ഞാൻ WhatsApp വഴി നൽകിയ തമ്പ്/എമോജി പ്രതികരണം (👍 / ☑️ / OK / YES) മുഖേനയാണ് നൽകുന്നതെന്നും, അത് എന്റെ ഡിജിറ്റൽ സമ്മതമായി (Digital Consent) കണക്കാക്കാവുന്നതാണെന്നും ഞാൻ അംഗീകരിക്കുന്നു.

ഭാവിയിൽ ഇതുമായി ബന്ധപ്പെട്ട് യാതൊരു തർക്കത്തിനും, പരാതിക്കും, റീഫണ്ട് ആവശ്യത്തിനും ഞാൻ അവകാശവാദം ഉന്നയിക്കുന്നതല്ലെന്ന് ഇതിലൂടെ ഞാൻ വ്യക്തമായി അറിയിക്കുന്നു.
                        </p>
                        <div style="margin-top: 40px; display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                            
                           
                        </div>
                        <div style="margin-top: 30px;">
                            <label style="display: block; color: #666; font-weight: 500; margin-bottom: 8px;">ഒപ്പ് (Signature)</label>
                            <div style="border-bottom: 2px solid #e91e63; padding: 20px 10px; background: white; border-radius: 4px;">
                                <input type="text" name="declarationSignature" placeholder="Type your name as signature" style="width: 100%; border: none; font-size: 18px; font-style: italic; color: #e91e63;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Second Declaration - Service Scheme Payment -->
                <div style="margin-bottom: 30px;">
                    <h3 style="color: #e91e63; border-bottom: 2px solid #e91e63; padding-bottom: 10px; margin-bottom: 20px;">✍️ സത്യപ്രസ്ഥാപനം</h3>
                    <div style="background: #fef5f8; border: 2px solid #e91e63; border-radius: 10px; padding: 25px;">
                        <p style="color: #333; line-height: 2.2; font-size: 15px; margin: 0;">
                            <span style="border-bottom: 2px dotted #666; display: inline-block; min-width: 150px; padding: 0 10px;">
                                <input type="text" name="serviceScheme" style="border: none; background: transparent; width: 140px; text-align: center;" placeholder="">
                            </span>
                            സർവീസ് സ്കീം പ്രകാരം അഡ്വാൻസ് തുക 
                            <span style="border-bottom: 2px dotted #666; display: inline-block; min-width: 100px; padding: 0 10px;">
                                <input type="text" name="advanceAmountRupees" style="border: none; background: transparent; width: 90px; text-align: center;" placeholder="">
                            </span>
                            രൂപ അടച്ചിരിക്കുന്നു. ഇതിനു പുറമെ പരസ്പരം ഉണ്ടാക്കിയ ധാരണ അനുസരിച്ചു ഉറപ്പിച്ച കമ്മീഷൻ തുക 
                            <span style="border-bottom: 2px dotted #666; display: inline-block; min-width: 100px; padding: 0 10px;">
                                <input type="text" name="commissionAmountRupees" style="border: none; background: transparent; width: 90px; text-align: center;" placeholder="">
                            </span>
                            രൂപ (
                            <span style="border-bottom: 2px dotted #666; display: inline-block; min-width: 150px; padding: 0 10px;">
                                <input type="text" name="commissionAmountInWords" style="border: none; background: transparent; width: 140px; text-align: center;" placeholder="">
                            </span>
                            ) രൂപ മാത്രം വിവാഹം ഉറപ്പിച്ച അന്ന് തന്നെ ഓഫീസിൽ അടച്ചു രസീത് വാങ്ങുന്നതാണെന്ന് സത്യ ബോധ്യപെടുത്തുന്നു.
                        </p>
                        <div style="margin-top: 40px; display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                            <div>
                                <label style="display: block; color: #666; font-weight: 600; margin-bottom: 8px;">സ്ഥലം (Place)</label>
                                <input type="text" name="declaration2Place" style="width: 100%; padding: 12px; border: none; border-bottom: 2px solid #e91e63; background: transparent; font-size: 16px;">
                            </div>
                            <div>
                                <label style="display: block; color: #666; font-weight: 600; margin-bottom: 8px;">തീയതി (Date)</label>
                                <input type="date" name="declaration2Date" style="width: 100%; padding: 12px; border: none; border-bottom: 2px solid #e91e63; background: transparent; font-size: 16px;">
                            </div>
                        </div>
                        <div style="margin-top: 30px;">
                            <label style="display: block; color: #666; font-weight: 600; margin-bottom: 8px;">ഒപ്പ് (Signature)</label>
                            <div style="border-bottom: 2px solid #e91e63; padding: 20px 10px; background: white; border-radius: 4px;">
                                <input type="text" name="declaration2Signature" placeholder="Type your name as signature" style="width: 100%; border: none; font-size: 18px; font-style: italic; color: #e91e63; font-weight: 500;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 15px; justify-content: flex-end; margin-top: 30px;">
                    <button type="button" id="saveBtn"
                        style="padding: 12px 30px; background: #4CAF50; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 16px;">
                        💾 Save Form
                    </button>
                    <button type="button" id="pdfBtn"
                        style="padding: 12px 30px; background: #e91e63; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 16px;">
                        📄 Generate PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>/* CACHE_BUST_1767767403 */
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registrationForm');
    const fetchBtn = document.getElementById('fetchBtn');
    const saveBtn = document.getElementById('saveBtn');
    const pdfBtn = document.getElementById('pdfBtn');
    const imidInput = document.getElementById('imid');
    const fetchStatus = document.getElementById('fetchStatus');

    // Fetch Profile Data
    fetchBtn.addEventListener('click', async function() {
        const imid = imidInput.value.trim();
        if (!imid) {
            fetchStatus.innerHTML = '<span style="color: red;">⚠️ Please enter an IMID</span>';
            return;
        }

        fetchStatus.innerHTML = '<span style="color: #2196F3;">⏳ Fetching profile...</span>';
        fetchBtn.disabled = true;

        try {
            const response = await fetch(`/api/registration-form/profile/fetch2/${imid}`);
            const result = await response.json();

            if (result.success && result.data) {
                console.log("Full API Response:", result);
                console.log("All data keys:", Object.keys(result.data));
                console.log("Member Data:", result.data.member);
                console.log("User gender:", result.data.user ? result.data.user.gender : "N/A");
                console.log("Member gender:", result.data.member ? result.data.member.gender : "N/A");
                // Fill form with fetched data
                const data = result.data;
                
                if (data.user) {
                    form.querySelector('[name="firstName"]').value = data.user.first_name || '';
                    form.querySelector('[name="lastName"]').value = data.user.last_name || '';
                    form.querySelector('[name="email"]').value = data.user.email || '';
                    form.querySelector('[name="phone"]').value = data.user.phone || '';
                }

                // Populate Package Information
                if (data.package) {
                    form.querySelector('[name="package"]').value = data.package.name || '';
                    form.querySelector('[name="amount"]').value = data.package.price || '';
                    
                    // Set Paid On to the member creation date
                    if (data.package.member_since) {
                        const paidOnDate = data.package.member_since.split(' ')[0];
                        form.querySelector('[name="paidOn"]').value = paidOnDate;
                    }
                    
                    // Set Duration to the package expiry date
                    form.querySelector('[name="duration"]').value = data.package.validity_date || '';
                    
                    // Set Months to the validity period (convert days to months)
                    const validityDays = data.package.validity || 0;
                    const validityMonths = Math.round(validityDays / 30);
                    form.querySelector('[name="months"]').value = validityMonths;
                }
                
                if (data.member) {
                        const genderValue = data.member.gender;
                    if (data.member.gender) {
                        console.log("Gender from API:", data.member.gender);
                        console.log("Gender type:", typeof data.member.gender);
                        const genderSelect = form.querySelector('select[name="gender"]');
                        console.log("Gender select element found:", genderSelect);
                        console.log("Setting value to:", genderValue);
                        if (genderSelect) {
                            genderSelect.value = genderValue;
                            genderSelect.dispatchEvent(new Event("change"));
                            console.log("Gender value after setting:", genderSelect.value);
                        }
                    }
                    
                    // Populate Date of Birth from birthday field
                    if (data.member.birthday) {
                        const birthday = data.member.birthday.split(' ')[0]; // Get only date part
                        const dobInput = form.querySelector('input[name="dateOfBirth"]');
                        if (dobInput) {
                            dobInput.value = birthday;
                            
                            // Calculate and populate Age
                            const birthDate = new Date(birthday);
                            const today = new Date();
                            let age = today.getFullYear() - birthDate.getFullYear();
                            const monthDiff = today.getMonth() - birthDate.getMonth();
                            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                                age--;
                            }
                            const ageInput = form.querySelector('input[name="age"]');
                            if (ageInput) {
                                ageInput.value = age;
                            }
                        }
                    }
                    
                    // Populate Star if available
                    const starInput = form.querySelector('input[name="star"]');
                    if (starInput && data.member.star) {
                        starInput.value = data.member.star;
                    }
                }
                
                if (data.address) {
                    console.log("Address data:", data.address);
                    // If address has the old structure with separate fields
                    if (data.address.house_name) {
                        form.querySelector('[name="houseName"]').value = data.address.house_name || '';
                        form.querySelector('[name="po"]').value = data.address.po || '';
                        form.querySelector('[name="via"]').value = data.address.via || '';
                        form.querySelector('[name="city"]').value = data.address.district || '';
                    } 
                    // If address has the new structure with semicolon-separated values
                    else if (data.address.address) {
                        const addressParts = data.address.address.split(';');
                        if (addressParts.length >= 4) {
                            form.querySelector('[name="houseName"]').value = addressParts[0] || '';
                            form.querySelector('[name="po"]').value = addressParts[1] || '';
                            form.querySelector('[name="via"]').value = addressParts[2] || '';
                            form.querySelector('[name="city"]').value = addressParts[3] || '';
                        } else {
                            // If format is different, just put the whole address in houseName
                            form.querySelector('[name="houseName"]').value = data.address.address || '';
                        }
                    }
                }
                
                if (data.education) {
                console.log("Education data:", data.education);
                    form.querySelector('[name="education"]').value = data.education.degree || '';
                    form.querySelector('[name="institution"]').value = data.education.institution || '';
                }
                
                console.log("Career data:", data.career);
                if (data.career) {
                    form.querySelector('[name="designation"]').value = data.career.designation || '';
                    form.querySelector('[name="company"]').value = data.career.company || '';
                }



                // Populate Physical Attributes
                if (data.physical) {
                    const heightInput = form.querySelector('input[name="height"]');
                    if (heightInput && data.physical.height) {
                        heightInput.value = data.physical.height;
                    }
                    const weightInput = form.querySelector('input[name="weight"]');
                    if (weightInput && data.physical.weight) {
                        weightInput.value = data.physical.weight;
                    }
                    const complexionInput = form.querySelector('input[name="complexion"]');
                    if (complexionInput && data.physical.complexion) {
                        complexionInput.value = data.physical.complexion;
                    }
                    const bodyTypeInput = form.querySelector('input[name="bodyType"]');
                    if (bodyTypeInput && data.physical.body_type) {
                        bodyTypeInput.value = data.physical.body_type;
                    }
                }

                // Populate Spiritual Background
                if (data.spiritual) {
                    const religionInput = form.querySelector('input[name="religion"]');
                    if (religionInput && data.spiritual.religion) {
                        religionInput.value = data.spiritual.religion;
                    }
                    const casteInput = form.querySelector('input[name="caste"]');
                    if (casteInput && data.spiritual.caste) {
                        casteInput.value = data.spiritual.caste;
                    }
                    const subCasteInput = form.querySelector('input[name="subCaste"]');
                    if (subCasteInput && data.spiritual.sub_caste) {
                        subCasteInput.value = data.spiritual.sub_caste;
                    }
                }

                // Populate Family Details
                if (data.family) {
                    const fatherNameInput = form.querySelector('input[name="fatherName"]');
                    if (fatherNameInput && data.family.father_name) {
                        fatherNameInput.value = data.family.father_name;
                    }
                    const motherNameInput = form.querySelector('input[name="motherName"]');
                    if (motherNameInput && data.family.mother_name) {
                        motherNameInput.value = data.family.mother_name;
                    }
                    const fatherOccupationInput = form.querySelector('input[name="fatherOccupation"]');
                    if (fatherOccupationInput && data.family.father_occupation) {
                        fatherOccupationInput.value = data.family.father_occupation;
                    }
                    const motherOccupationInput = form.querySelector('input[name="motherOccupation"]');
                    if (motherOccupationInput && data.family.mother_occupation) {
                        motherOccupationInput.value = data.family.mother_occupation;
                    }
                    const siblingsInput = form.querySelector('input[name="siblings"]');
                    if (siblingsInput && data.family.no_of_siblings) {
                        siblingsInput.value = data.family.no_of_siblings;
                    }
                }
                // Populate Partner Preferences
                if (!data.partner) { console.log("❌ NO PARTNER DATA IN RESPONSE"); }
                if (data.partner) {
                    console.log("✅ PARTNER DATA FOUND:", data.partner);
                    const prefs = data.partner;
                    console.log("Partner Preferences Data:", prefs);
                    
                    // Preferred Age
                    if (prefs.preferred_age_min && prefs.preferred_age_max) {
                        const preferredAgeInput = form.querySelector('input[name="preferredAge"]');
                        if (preferredAgeInput) {
                            preferredAgeInput.value = `${prefs.preferred_age_min}-${prefs.preferred_age_max}`;
                            console.log("Preferred Age set to:", `${prefs.preferred_age_min}-${prefs.preferred_age_max}`);
                        }
                    }
                    
                    // Preferred Height
                    if (prefs.height) {
                        const preferredHeightInput = form.querySelector('input[name="preferredHeight"]');
                        if (preferredHeightInput) {
                            preferredHeightInput.value = prefs.height;
                        }
                    }
                    
                    // Preferred Education
                    if (prefs.preferred_education) {
                        const preferredEducationInput = form.querySelector('input[name="preferredEducation"]');
                        if (preferredEducationInput) {
                            preferredEducationInput.value = prefs.preferred_education;
                        }
                    }
                    
                    // Preferred District
                    if (prefs.preferred_location) {
                        const preferredDistrictInput = form.querySelector('input[name="preferredDistrict"]');
                        if (preferredDistrictInput) {
                            preferredDistrictInput.value = prefs.preferred_location;
                        }
                    }
                    
                    // Preferred Family Value
                    if (prefs.preferred_family_value_id) {
                        const preferredFamilyValueInput = form.querySelector('input[name="preferredFamilyValue"]');
                        if (preferredFamilyValueInput) {
                            preferredFamilyValueInput.value = prefs.preferred_family_value_id;
                        }
                    }
                    
                    // Preferred Complexion
                    if (prefs.complexion) {
                        const preferredComplexionInput = form.querySelector('input[name="preferredComplexion"]');
                        if (preferredComplexionInput) {
                            preferredComplexionInput.value = prefs.complexion;
                        }
                    }
                }

                fetchStatus.innerHTML = '<span style="color: #4CAF50;">✅ Profile fetched successfully!</span>';
            } else {
                fetchStatus.innerHTML = '<span style="color: red;">⚠️ Profile not found</span>';
            }
        } catch (error) {
            fetchStatus.innerHTML = '<span style="color: red;">❌ Error fetching profile</span>';
            console.error('Error:', error);
        } finally {
            fetchBtn.disabled = false;
        }
    });

    // Save Form
    saveBtn.addEventListener('click', async function() {
        const formData = new FormData(form);
        saveBtn.disabled = true;
        saveBtn.textContent = '⏳ Saving...';

        try {
            const response = await fetch('/api/registration-form/save', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                alert('✅ Form saved successfully!');
                saveBtn.textContent = '💾 Save Form';
            } else {
                alert('❌ Error: ' + result.message);
                saveBtn.textContent = '💾 Save Form';
            }
        } catch (error) {
            alert('❌ Error saving form');
            console.error('Error:', error);
            saveBtn.textContent = '💾 Save Form';
        } finally {
            saveBtn.disabled = false;
        }
    });

    // Generate PDF
    pdfBtn.addEventListener('click', async function() {
        const formData = new FormData(form);
        pdfBtn.disabled = true;
        pdfBtn.textContent = '⏳ Generating PDF...';

        try {
            const response = await fetch('/api/registration-form/pdf', {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `Registration_Form_${imidInput.value}.pdf`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
                pdfBtn.textContent = '📄 Generate PDF';
            } else {
                alert('❌ Error generating PDF');
                pdfBtn.textContent = '📄 Generate PDF';
            }
        } catch (error) {
            alert('❌ Error generating PDF');
            console.error('Error:', error);
            pdfBtn.textContent = '📄 Generate PDF';
        } finally {
            pdfBtn.disabled = false;
        }
    });
});
</script>
@endsection
