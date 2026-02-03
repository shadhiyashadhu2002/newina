<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>INA Matrimony - Registration Form</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            line-height: 1.6;
        }
        
        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: white;
            padding: 30px;
        }
        
        .header {
            background: linear-gradient(135deg, #e83e8c 0%, #d91e63 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 5px 5px 0 0;
            margin: -30px -30px 30px -30px;
        }
        
        .header-logo {
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .header h1 {
            font-size: 28px;
            margin: 10px 0;
            font-weight: bold;
        }
        
        .header p {
            font-size: 12px;
            margin: 5px 0;
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section-title {
            background-color: #e83e8c;
            color: white;
            padding: 10px 15px;
            font-weight: bold;
            font-size: 14px;
            border-left: 4px solid #d91e63;
            margin-bottom: 15px;
        }
        
        .section-content {
            padding: 0 15px;
        }
        
        .form-row {
            display: flex;
            gap: 30px;
            margin-bottom: 15px;
        }
        
        .form-row.full {
            flex-direction: column;
        }
        
        .form-group {
            flex: 1;
            min-width: 200px;
        }
        
        .form-group.full {
            width: 100%;
        }
        
        .form-label {
            font-weight: bold;
            color: #333;
            font-size: 12px;
            margin-bottom: 3px;
            display: block;
        }
        
        .form-value {
            border-bottom: 1px solid #ddd;
            padding: 8px 0;
            min-height: 20px;
            font-size: 12px;
            color: #555;
            word-break: break-word;
        }
        
        .imid-box {
            background-color: #f9f9f9;
            border: 2px solid #e83e8c;
            padding: 10px;
            border-radius: 3px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .imid-label {
            font-size: 11px;
            color: #666;
            font-weight: bold;
        }
        
        .imid-value {
            font-size: 18px;
            font-weight: bold;
            color: #e83e8c;
        }
        
        .signature-section {
            margin-top: 40px;
            display: flex;
            gap: 80px;
        }
        
        .signature-line {
            flex: 1;
            border-top: 1px solid #333;
            padding-top: 5px;
            text-align: center;
            font-size: 11px;
        }
        
        .signature-name {
            margin-top: 5px;
            font-size: 12px;
            font-weight: bold;
            color: #333;
        }
        
        .declaration-box {
            background-color: #fff9f0;
            border: 1px solid #e83e8c;
            padding: 15px;
            margin: 15px 0;
            font-size: 11px;
            line-height: 1.6;
            color: #333;
            border-radius: 3px;
        }
        
        .declaration-title {
            font-weight: bold;
            color: #e83e8c;
            margin-bottom: 10px;
            font-size: 12px;
        }
        
        .footer {
            text-align: center;
            font-size: 10px;
            color: #999;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
        }
        
        @media print {
            body {
                background-color: white;
            }
            .container {
                margin: 0;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-logo">🌸 INA</div>
            <h1>INA MATRIMONY</h1>
            <p>www.inamatrimony.com</p>
            <p>9037054010 | 9072884010</p>
            <p>Registration Form</p>
        </div>
        
        <!-- IMID Box -->
        <div class="imid-box">
            <div class="imid-label">IMID</div>
            <div class="imid-value">{{ $imid ?? 'N/A' }}</div>
        </div>
        
        <!-- Profile Information Section -->
        <div class="section">
            <div class="section-title">Profile Information</div>
            <div class="section-content">
                @if(isset($data['Package']) || isset($data['Amount']))
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Package</label>
                        <div class="form-value">{{ $data['Package'] ?? 'N/A' }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Amount</label>
                        <div class="form-value">{{ $data['Amount'] ?? 'N/A' }}</div>
                    </div>
                </div>
                @endif
                
                @if(isset($data['Paid On']) || isset($data['Duration']))
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Paid On</label>
                        <div class="form-value">{{ $data['Paid On'] ?? 'N/A' }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Duration</label>
                        <div class="form-value">{{ $data['Duration'] ?? 'N/A' }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Personal Information Section -->
        <div class="section">
            <div class="section-title">Personal Information</div>
            <div class="section-content">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Name</label>
                        <div class="form-value">
                            @if(isset($data['First Name']) || isset($data['Last Name']))
                                {{ $data['First Name'] ?? '' }} {{ $data['Last Name'] ?? '' }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Gender</label>
                        <div class="form-value">{{ $data['Gender'] ?? 'N/A' }}</div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <div class="form-value">{{ $data['Email'] ?? 'N/A' }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <div class="form-value">{{ $data['Phone'] ?? 'N/A' }}</div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Date of Birth</label>
                        <div class="form-value">{{ $data['Date Of Birth'] ?? 'N/A' }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Age</label>
                        <div class="form-value">{{ $data['Age'] ?? 'N/A' }}</div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Star</label>
                        <div class="form-value">{{ $data['Star'] ?? 'N/A' }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Marital Status</label>
                        <div class="form-value">{{ $data['Marital Status'] ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Address Information Section -->
        <div class="section">
            <div class="section-title">Address Information</div>
            <div class="section-content">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">House Name</label>
                        <div class="form-value">{{ $data['House Name'] ?? 'N/A' }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Post Office</label>
                        <div class="form-value">{{ $data['Post Office'] ?? 'N/A' }}</div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Via</label>
                        <div class="form-value">{{ $data['Via'] ?? 'N/A' }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">City</label>
                        <div class="form-value">{{ $data['City'] ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Education & Career Section -->
        <div class="section">
            <div class="section-title">Education & Career</div>
            <div class="section-content">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Education</label>
                        <div class="form-value">{{ $data['Education'] ?? 'N/A' }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Institution</label>
                        <div class="form-value">{{ $data['Institution'] ?? 'N/A' }}</div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Designation</label>
                        <div class="form-value">{{ $data['Designation'] ?? 'N/A' }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Company</label>
                        <div class="form-value">{{ $data['Company'] ?? 'N/A' }}</div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Annual Salary</label>
                        <div class="form-value">{{ $data['Annual Salary'] ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Physical Attributes Section -->
        <div class="section">
            <div class="section-title">Physical Attributes</div>
            <div class="section-content">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Height</label>
                        <div class="form-value">{{ $data['Height Cm'] ?? 'N/A' }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Weight</label>
                        <div class="form-value">{{ $data['Weight Kg'] ?? 'N/A' }}</div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Blood Group</label>
                        <div class="form-value">{{ $data['Blood Group'] ?? 'N/A' }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Complexion</label>
                        <div class="form-value">{{ $data['Complexion'] ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Spiritual Background Section -->
        <div class="section">
            <div class="section-title">Spiritual Background</div>
            <div class="section-content">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Religion</label>
                        <div class="form-value">{{ $data['Religion'] ?? 'N/A' }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Caste</label>
                        <div class="form-value">{{ $data['Caste'] ?? 'N/A' }}</div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Sub Caste</label>
                        <div class="form-value">{{ $data['Sub Caste'] ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Family Details Section -->
        <div class="section">
            <div class="section-title">Family Details</div>
            <div class="section-content">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Father</label>
                        <div class="form-value">{{ $data['Father'] ?? 'N/A' }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Father Occupation</label>
                        <div class="form-value">{{ $data['Father Occupation'] ?? 'N/A' }}</div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Mother</label>
                        <div class="form-value">{{ $data['Mother'] ?? 'N/A' }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Mother Occupation</label>
                        <div class="form-value">{{ $data['Mother Occupation'] ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Digital Declaration Section -->
        <div class="section">
            <div class="section-title">Digital Declaration</div>
            <div class="section-content">
                <div class="declaration-box">
                    <div class="declaration-title">Terms & Conditions</div>
                    <p>I hereby declare that all the information provided in this registration form is true and correct to the best of my knowledge. I agree to abide by the rules and regulations of INA Matrimony. I acknowledge that this organization is not responsible for any discrepancies in the information provided. I have read and accepted the Terms & Conditions, and consent to contact via WhatsApp or other means as required.</p>
                </div>
                
                <div class="form-row full">
                    <div class="form-group">
                        <label class="form-label">Signature</label>
                        <div class="form-value">{{ $data['Declaration Signature'] ?? '_____________________' }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Declaration Section -->
        <div class="section">
            <div class="section-title">Declaration</div>
            <div class="section-content">
                <div class="declaration-box">
                    <p>I declare that as per the service scheme, the commission amount of ₹ 12500 or the agreed amount has been received as advance, and the remaining amount as per the agreement needs to be paid upon successful match/marriage.</p>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Place</label>
                        <div class="form-value">{{ $data['Declaration Place'] ?? 'KOTTAKODI' }}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date</label>
                        <div class="form-value">{{ $data['Declaration Date'] ?? date('Y-m-d') }}</div>
                    </div>
                </div>
                
                <div class="form-row full">
                    <div class="form-group">
                        <label class="form-label">Signature</label>
                        <div class="form-value">{{ $data['Declaration Signature 2'] ?? '_____________________' }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>This is an electronically generated document. Document generated on {{ date('d-m-Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
