<!DOCTYPE html>
<html lang="ml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Noto Sans Malayalam', 'Arial', sans-serif;
            font-size: 8.5pt;
            line-height: 1.3;
            color: #000;
        }
        
        .container {
            width: 100%;
            padding: 0;
        }
        
        /* Pink Header with Logo on LEFT */
        .header {
            background: linear-gradient(135deg, #e91e63 0%, #d81b60 100%);
            color: white;
            padding: 20px 25px;
            position: relative;
            margin-bottom: 0;
            display: table;
            width: 100%;
        }
        
        .header-left {
            display: table-cell;
            vertical-align: middle;
            width: 80px;
        }
        
        .header .logo {
            width: 65px;
            height: 65px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24pt;
            color: #e91e63;
            font-weight: bold;
            margin: 0 auto;
        }
        
        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            padding-left: 20px;
        }
        
        .header h1 {
            font-size: 28pt;
            font-weight: bold;
            margin-bottom: 8px;
            letter-spacing: 3px;
        }
        
        .header .contact {
            font-size: 10pt;
            margin: 3px 0;
            opacity: 0.95;
        }
        
        .header .subtitle {
            font-size: 13pt;
            font-weight: 600;
            margin-top: 8px;
            letter-spacing: 1px;
        }
        
        /* Content Area */
        .content {
            padding: 8px 14px;
        }
        
        /* Section with border */
        .section-box {
            border: 2px solid #e91e63;
            margin-bottom: 0;
            background: white;
        }
        
        /* Profile Information Header */
        .profile-header {
            background-color: #fff0f5;
            color: #e91e63;
            padding: 6px 12px;
            font-size: 10pt;
            font-weight: bold;
            text-align: left;
            border-bottom: 2px solid #e91e63;
        }
        
        /* IMID Row */
        .imid-row {
            display: table;
            width: 100%;
            border-bottom: 1px solid #ddd;
            background-color: #fafafa;
        }
        
        .imid-row > div {
            display: table-cell;
            vertical-align: middle;
            padding: 5px 12px;
        }
        
        .imid-row .label {
            width: 15%;
            font-weight: bold;
            font-size: 8.5pt;
        }
        
        .imid-row .value {
            width: 85%;
            font-size: 8.5pt;
        }
        
        /* Section Headers */
        .section-title {
            background-color: #e91e63;
            color: white;
            padding: 4px 12px;
            font-size: 9.5pt;
            font-weight: bold;
            text-align: left;
            border-bottom: 1px solid #c2185b;
        }
        
        /* Package Info - 4 columns */
        .package-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        
        .package-row {
            display: table-row;
        }
        
        .package-cell {
            display: table-cell;
            border-bottom: 1px solid #ddd;
            padding: 3px 10px;
            vertical-align: top;
        }
        
        .package-cell.label {
            width: 13%;
            font-weight: bold;
            background-color: #fafafa;
            font-size: 8.5pt;
        }
        
        .package-cell.value {
            width: 37%;
            font-size: 8.5pt;
        }
        
        /* Two Column Grid */
        .two-column {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        
        .field-row {
            display: table-row;
        }
        
        .field-cell {
            display: table-cell;
            border-bottom: 1px solid #ddd;
            padding: 3px 10px;
            vertical-align: top;
        }
        
        .field-cell.label {
            width: 18%;
            font-weight: bold;
            background-color: #fafafa;
            font-size: 8.5pt;
        }
        
        .field-cell.value {
            width: 32%;
            font-size: 8.5pt;
        }
        
        /* Full Width Row */
        .full-row {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        
        .full-cell {
            display: table-cell;
            border-bottom: 1px solid #ddd;
            padding: 3px 10px;
            vertical-align: top;
        }
        
        .full-cell.label {
            width: 18%;
            font-weight: bold;
            background-color: #fafafa;
            font-size: 8.5pt;
        }
        
        .full-cell.value {
            width: 82%;
            font-size: 8.5pt;
        }
        
        /* Declaration Section */
        .declaration {
            margin: 15px 0;
            padding: 12px 15px;
            border: 2px solid #e91e63;
            background-color: #fff5f8;
            font-size: 8.5pt;
            line-height: 1.6;
        }
        
        .declaration h3 {
            font-size: 11pt;
            margin-bottom: 10px;
            color: #e91e63;
            font-family: 'Noto Sans Malayalam', 'Arial', sans-serif;
            font-weight: bold;
        }
        
        .declaration p {
            margin: 8px 0;
            text-align: justify;
            font-family: 'Noto Sans Malayalam', 'Arial', sans-serif;
        }
        
        .signature-section {
            display: table;
            width: 100%;
            margin-top: 15px;
        }
        
        .signature-block {
            display: table-cell;
            width: 33.33%;
            padding: 5px 10px;
            vertical-align: top;
        }
        
        .signature-block .label {
            font-weight: bold;
            font-size: 9pt;
            margin-bottom: 4px;
            color: #555;
        }
        
        .signature-block .value {
            border-bottom: 1px solid #333;
            min-height: 30px;
            padding-top: 20px;
            font-size: 9pt;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            padding: 8px 0;
            font-size: 8pt;
            color: #666;
            background-color: #f5f5f5;
            border-top: 2px solid #e91e63;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
        
        /* Page Break */
        .page-break {
            page-break-after: always;
        }
        
        /* Page 2 specific */
        .page-2 {
            padding-top: 40px;
        }
        
        /* Ensure Malayalam text renders properly */
        .malayalam {
            font-family: 'Noto Sans Malayalam', 'Rachana', 'Meera', sans-serif;
        }
    </style>
</head>
<body>
    <!-- PAGE 1: Profile Information -->
    <div class="container">
        <!-- Pink Header with Logo on LEFT -->
        <div class="header">
            <div class="header-left">
                <div class="logo">ഇണ</div>
            </div>
            <div class="header-right">
                <h1>INA MATRIMONY</h1>
                <div class="contact">www.inamatrimony.com</div>
                <div class="contact">9037054010 | 9072894010</div>
                <div class="subtitle">Registration Form</div>
            </div>
        </div>
        
        <div class="content">
            <div class="section-box">
                <!-- Profile Information Header -->
                <div class="profile-header">Profile Information</div>
                
                <!-- IMID -->
                <div class="imid-row">
                    <div class="label">IMID:</div>
                    <div class="value">{{ $data['IMID'] ?? 'N/A' }}</div>
                </div>
                
                <!-- Package Information -->
                <div class="section-title">Package Information</div>
                <div class="package-grid">
                    <div class="package-row">
                        <div class="package-cell label">Package:</div>
                        <div class="package-cell value">{{ $data['Package'] ?? 'N/A' }}</div>
                        <div class="package-cell label">Amount:</div>
                        <div class="package-cell value">{{ $data['Amount'] ?? 'N/A' }}</div>
                    </div>
                    <div class="package-row">
                        <div class="package-cell label">Paid On:</div>
                        <div class="package-cell value">{{ $data['Paid On'] ?? 'N/A' }}</div>
                        <div class="package-cell label">Duration:</div>
                        <div class="package-cell value">{{ $data['Duration'] ?? 'N/A' }}</div>
                    </div>
                </div>
                
                <!-- Personal Information -->
                <div class="section-title">Personal Information</div>
                <div class="two-column">
                    <div class="field-row">
                        <div class="field-cell label">Name:</div>
                        <div class="field-cell value">{{ $data['Name'] ?? 'N/A' }}</div>
                        <div class="field-cell label">Email:</div>
                        <div class="field-cell value">{{ $data['Email'] ?? 'N/A' }}</div>
                    </div>
                    <div class="field-row">
                        <div class="field-cell label">Gender:</div>
                        <div class="field-cell value">{{ $data['Gender'] ?? 'N/A' }}</div>
                        <div class="field-cell label">Phone:</div>
                        <div class="field-cell value">{{ $data['Phone'] ?? 'N/A' }}</div>
                    </div>
                    <div class="field-row">
                        <div class="field-cell label">DOB:</div>
                        <div class="field-cell value">{{ $data['Date of Birth'] ?? 'N/A' }}</div>
                        <div class="field-cell label">Age:</div>
                        <div class="field-cell value">{{ $data['Age'] ?? 'N/A' }}</div>
                    </div>
                    <div class="field-row">
                        <div class="field-cell label">Star:</div>
                        <div class="field-cell value">{{ $data['Star'] ?? 'N/A' }}</div>
                        <div class="field-cell label">Marital Status:</div>
                        <div class="field-cell value">{{ $data['Marital Status'] ?? 'N/A' }}</div>
                    </div>
                </div>
                
                <!-- Address Information -->
                <div class="section-title">Address Information</div>
                <div class="two-column">
                    <div class="field-row">
                        <div class="field-cell label">House:</div>
                        <div class="field-cell value">{{ $data['House'] ?? 'N/A' }}</div>
                        <div class="field-cell label">Via:</div>
                        <div class="field-cell value">{{ $data['Via'] ?? 'N/A' }}</div>
                    </div>
                    <div class="field-row">
                        <div class="field-cell label">Post Office:</div>
                        <div class="field-cell value">{{ $data['Post Office'] ?? 'N/A' }}</div>
                        <div class="field-cell label">City:</div>
                        <div class="field-cell value">{{ $data['City'] ?? 'N/A' }}</div>
                    </div>
                </div>
                
                <!-- Education & Career -->
                <div class="section-title">Education & Career</div>
                <div class="two-column">
                    <div class="field-row">
                        <div class="field-cell label">Education:</div>
                        <div class="field-cell value">{{ $data['Education'] ?? 'N/A' }}</div>
                        <div class="field-cell label">Institution:</div>
                        <div class="field-cell value">{{ $data['Institution'] ?? 'N/A' }}</div>
                    </div>
                    <div class="field-row">
                        <div class="field-cell label">Designation:</div>
                        <div class="field-cell value">{{ $data['Designation'] ?? 'N/A' }}</div>
                        <div class="field-cell label">Company:</div>
                        <div class="field-cell value">{{ $data['Company'] ?? 'N/A' }}</div>
                    </div>
                </div>
                
                <!-- Physical Attributes -->
                <div class="section-title">Physical Attributes</div>
                <div class="two-column">
                    <div class="field-row">
                        <div class="field-cell label">Height:</div>
                        <div class="field-cell value">{{ $data['Height'] ?? 'N/A' }}</div>
                        <div class="field-cell label">Weight:</div>
                        <div class="field-cell value">{{ $data['Weight'] ?? 'N/A' }}</div>
                    </div>
                    <div class="field-row">
                        <div class="field-cell label">Blood Group:</div>
                        <div class="field-cell value">{{ $data['Blood Group'] ?? 'N/A' }}</div>
                        <div class="field-cell label">Complexion:</div>
                        <div class="field-cell value">{{ $data['Complexion'] ?? 'N/A' }}</div>
                    </div>
                </div>
                
                <!-- Spiritual Background -->
                <div class="section-title">Spiritual Background</div>
                <div class="two-column">
                    <div class="field-row">
                        <div class="field-cell label">Religion:</div>
                        <div class="field-cell value">{{ $data['Religion'] ?? 'N/A' }}</div>
                        <div class="field-cell label">Caste:</div>
                        <div class="field-cell value">{{ $data['Caste'] ?? 'N/A' }}</div>
                    </div>
                </div>
                <div class="full-row">
                    <div class="full-cell label">Sub Caste:</div>
                    <div class="full-cell value">{{ $data['Sub Caste'] ?? 'N/A' }}</div>
                </div>
                
                <!-- Family Details -->
                <div class="section-title">Family Details</div>
                <div class="two-column">
                    <div class="field-row">
                        <div class="field-cell label">Father:</div>
                        <div class="field-cell value">{{ $data['Father'] ?? 'N/A' }}</div>
                        <div class="field-cell label">Father Occupation:</div>
                        <div class="field-cell value">{{ $data['Father Occupation'] ?? 'N/A' }}</div>
                    </div>
                    <div class="field-row">
                        <div class="field-cell label">Mother:</div>
                        <div class="field-cell value">{{ $data['Mother'] ?? 'N/A' }}</div>
                        <div class="field-cell label">Mother Occupation:</div>
                        <div class="field-cell value">{{ $data['Mother Occupation'] ?? 'N/A' }}</div>
                    </div>
                    <div class="field-row">
                        <div class="field-cell label">Brothers (Married):</div>
                        <div class="field-cell value">{{ $data['Brothers (Married)'] ?? 'N/A' }}</div>
                        <div class="field-cell label">Sisters (Married):</div>
                        <div class="field-cell value">{{ $data['Sisters (Married)'] ?? 'N/A' }}</div>
                    </div>
                </div>
                
                <!-- Partner Preferences -->
                <div class="section-title">Partner Preferences</div>
                <div class="two-column">
                    <div class="field-row">
                        <div class="field-cell label">Age:</div>
                        <div class="field-cell value">{{ $data['Partner Age'] ?? 'N/A' }}</div>
                        <div class="field-cell label">Height:</div>
                        <div class="field-cell value">{{ $data['Partner Height'] ?? 'N/A' }}</div>
                    </div>
                    <div class="field-row">
                        <div class="field-cell label">Education:</div>
                        <div class="field-cell value">{{ $data['Partner Education'] ?? 'N/A' }}</div>
                        <div class="field-cell label">District:</div>
                        <div class="field-cell value">{{ $data['Partner District'] ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Page Break -->
    <div class="page-break"></div>
    
    <!-- PAGE 2: Declarations Only -->
    <div class="container page-2">
        <div class="content">
            <!-- Digital Declaration -->
            <div class="declaration malayalam">
                <h3>ഡിജിറ്റൽ പ്രഖ്യാപനം</h3>
                <p>ഞാൻ, ഇണ മാട്രിമോണിയുടെ എല്ലാ സർവീസ് നിബന്ധനകളും (Terms & Conditions) പൂർണമായി മനസ്സിലാക്കിയതായും, യാതൊരു സമ്മർദ്ധവും ഇല്ലാതെ അവയെ സ്വമേധയാ അംഗീകരിക്കുന്നതായും ഇതിലൂടെ സ്ഥിരീകരിക്കുന്നു. ഇണ മാട്രിമോണി സർവീസിനായി ഞാൻ അടച്ചിരിക്കുന്ന സർവീസ് അഡ്വാൻസ് തുക റീഫണ്ട് ലഭിക്കുന്നതല്ല (Non-Refundable) എന്ന കാര്യം എനിക്ക് വ്യക്തമായി അറിയാവുന്നതും, അതിന് ഞാൻ പൂർണ സമ്മതം നൽകുന്നതുമാണ്.</p>
                <p>ഈ സമ്മതം ഞാൻ WhatsApp വഴി നൽകിയ തമ്പ്/ഏയ്മോജി പ്രതികരണം (OK / YES) മുഖേനയാണ് നൽകുന്നതെന്നും, അത് എന്റെ ഡിജിറ്റൽ സമ്മതമായി (Digital Consent) കണക്കാക്കാവുന്നതാണെന്നും ഞാൻ അംഗീകരിക്കുന്നു. ഇതുമായി ബന്ധപ്പെട്ട് യാതൊരു തർക്കത്തിനും, പരാതിക്കും, റീഫണ്ട് ആവശ്യത്തിനും ഞാൻ അവകാശവാദം ഉന്നയിക്കുന്നതല്ലെന്ന് ഇതിലൂടെ ഞാൻ വ്യക്തമായി അറിയിക്കുന്നു.</p>
                <div class="signature-section">
                    <div class="signature-block">
                        <div class="label">Signature</div>
                        <div class="value">{{ $data['Digital Declaration Signature'] ?? '' }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Declaration -->
            <div class="declaration malayalam">
                <h3>Declaration</h3>
                <p>{{ $data['Service Scheme'] ?? '_______________' }} സർവീസ് സ്കീം പ്രകാരം അഡ്വാൻസ് തുക {{ $data['Advance Amount Rupees'] ?? '_______' }} രൂപ അടച്ചിരിക്കുന്നു. ഇതിനു പുറമെ പരസ്പരം ഉണ്ടാക്കിയ ധാരണ അനുസരിച്ചു ഉറപ്പിച്ച കമ്മീഷൻ തുക {{ $data['Commission Amount Rupees'] ?? '_______' }} രൂപ ({{ $data['Commission Amount In Words'] ?? '_______________' }}) രൂപ മാത്രം വിവാഹം ഉറപ്പിച്ച അന്ന് തന്നെ ഓഫീസിൽ അടച്ചു രസീത് വാങ്ങുന്നതാണെന്ന് സത്യ ബോധ്യപെടുത്തുന്നു.</p>
                <div class="signature-section">
                    <div class="signature-block">
                        <div class="label">Place</div>
                        <div class="value">{{ $data['Declaration Place'] ?? '' }}</div>
                    </div>
                    <div class="signature-block">
                        <div class="label">Date</div>
                        <div class="value">{{ $data['Declaration Date'] ?? date('Y-m-d') }}</div>
                    </div>
                    <div class="signature-block">
                        <div class="label">Signature</div>
                        <div class="value">{{ $data['Declaration Signature 2'] ?? '' }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>Generated on {{ date('d-m-Y H:i:s') }} | INA Matrimony - All Rights Reserved</p>
        </div>
    </div>
</body>
</html>