<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RegistrationForm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class RegistrationFormController extends Controller
{
    public function fetchProfile($imid)
    {
        try {
            $user = User::where('code', $imid)->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profile not found'
                ], 404);
            }

            $member = DB::table('members')->where('user_id', $user->id)->first();
            $address = DB::table('addresses')->where('user_id', $user->id)->first();
            $physical = DB::table('physical_attributes')->where('user_id', $user->id)->first();

            // Get spiritual background with religion, caste, and subcaste
            $religion = '';
            $caste = '';
            $subCaste = '';
            $spiritual = DB::table('spiritual_backgrounds')->where('user_id', $user->id)->first();
            \Log::info('Spiritual data fetched:', ['spiritual' => $spiritual, 'user_id' => $user->id]);
            if ($spiritual) {
                if ($spiritual->religion_id) {
                    $religionRecord = DB::table('religions')->where('id', $spiritual->religion_id)->first();
                    $religion = $religionRecord ? $religionRecord->name : '';
                }
                if ($spiritual->caste_id) {
                    $casteRecord = DB::table('castes')->where('id', $spiritual->caste_id)->first();
                    $caste = $casteRecord ? $casteRecord->name : '';
                }
                if ($spiritual->sub_caste_id) {
                    $subCasteRecord = DB::table('sub_castes')->where('id', $spiritual->sub_caste_id)->first();
                    $subCaste = $subCasteRecord ? $subCasteRecord->name : '';
                }
            }
            
            // Add the name fields to spiritual object
            if ($spiritual) {
                $spiritual->religion = $religion;
                $spiritual->caste = $caste;
                $spiritual->sub_caste = $subCaste;
                \Log::info('After adding names to spiritual:', ['spiritual' => $spiritual, 'religion' => $religion, 'caste' => $caste, 'subCaste' => $subCaste]);
            } else {
                \Log::warning('Spiritual object is null for user_id: ' . $user->id);
            }
            
            $family = DB::table('families')->where('user_id', $user->id)->first();
            $education = DB::table('education')->where('user_id', $user->id)->where('is_highest_degree', 1)->first();
            $career = DB::table('careers')->where('user_id', $user->id)->where('present', 1)->first();
            $partner = DB::table('partner_expectations')->where('user_id', $user->id)->first();
            \Log::info('Partner data fetched:', ['partner' => $partner, 'user_id' => $user->id]);
            
            // Fetch package information
            $package = null;
            if ($member) {
                // Get the actual package details from packages table
                $packageDetails = DB::table('packages')->where('id', $member->current_package_id)->first();

                $package = [
                    'name' => $member->package_name ?? '',
                    'price' => $member->package_price ?? '',
                    'validity' => $packageDetails ? $packageDetails->validity : '',
                    'validity_date' => $member->package_validity ?? '',
                    'member_since' => $member->created_at ?? '',
                    'test_field' => 'THIS_IS_NEW_CODE',
                    'debug_timestamp' => date('Y-m-d H:i:s'), 'cache_buster' => time()
                ];
            }




            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user,
                    'member' => $member,
                    'package' => $package,
                    'address' => $address,
                    'physical' => $physical,
                    'spiritual' => $spiritual,
                    'family' => $family,
                    'education' => $education,
                    'career' => $career,
                    'partner' => $partner,
                    'PARTNER_TEST_MARKER' => 'PARTNER_CODE_IS_RUNNING_' . date('H:i:s'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching profile: ' . $e->getMessage()
            ], 500);
        }
    }

    public function save(Request $request)
    {
        try {
            $data = $request->all();
            
            if ($request->hasFile('attachment1')) {
                $path = $request->file('attachment1')->store('registration_forms', 'public');
                $data['attachment1_path'] = $path;
            }
            
            if ($request->hasFile('attachment2')) {
                $path = $request->file('attachment2')->store('registration_forms', 'public');
                $data['attachment2_path'] = $path;
            }

            if (isset($data['imid'])) {
                $user = User::where('code', $data['imid'])->first();
                if ($user) {
                    $data['user_id'] = $user->id;
                }
            }

            $data['created_by'] = auth()->id();

            $dbData = [];
            foreach ($data as $key => $value) {
                $snakeKey = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $key));
                $dbData[$snakeKey] = $value;
            }

            $form = RegistrationForm::updateOrCreate(
                ['imid' => $data['imid']],
                $dbData
            );

            return response()->json([
                'success' => true,
                'message' => 'Form saved successfully',
                'data' => $form
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving form: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generatePDF(Request $request)
    {
        Log::info("PDF Generation - Received data:", $request->all());
        try {
            $data = $request->all();

            // Map camelCase form fields to Title Case for PDF
            $fieldMap = [
                'imid' => 'IMID',
                'package' => 'Package',
                'amount' => 'Amount',
                'paidOn' => 'Paid On',
                'duration' => 'Duration',
                'months' => 'Months',
                'firstName' => 'First Name',
                'lastName' => 'Last Name',
                'email' => 'Email',
                'gender' => 'Gender',
                'phone' => 'Phone',
                'dateOfBirth' => 'Date Of Birth',
                'age' => 'Age',
                'star' => 'Star',
                'maritalStatus' => 'Marital Status',
                'children' => 'No Of Children',
                'annualSalary' => 'Annual Salary',
                'houseName' => 'House Name',
                'po' => 'Post Office',
                'via' => 'Via',
                'city' => 'City',
                'education' => 'Education',
                'institution' => 'Institution',
                'islamicStudies' => 'Islamic Studies',
                'designation' => 'Designation',
                'company' => 'Company',
                'height' => 'Height Cm',
                'weight' => 'Weight Kg',
                'bloodGroup' => 'Blood Group',
                'bodyType' => 'Body Type',
                'complexion' => 'Complexion',
                'disability' => 'Disability If Any',
                'religion' => 'Religion',
                'caste' => 'Caste',
                'subCaste' => 'Sub Caste',
                'father' => 'Father',
                'fatherOccupation' => 'Father Occupation',
                'mother' => 'Mother',
                'motherOccupation' => 'Mother Occupation',
                'brothers' => 'Brothers Married',
                'sistersMarried' => 'Sisters Married',
                'familyStandard' => 'Family Standard',
                'unmatchedStars' => 'Unmatched Stars',
                'preferredAge' => 'Preferred Age',
                'preferredHeight' => 'Preferred Height',
                'preferredEducation' => 'Preferred Education',
                'preferredDistrict' => 'Preferred District',
                'preferredFamilyValue' => 'Preferred Family Value',
                'preferredComplexion' => 'Preferred Complexion',
                'welcomeCallDetails' => 'Welcome Call Details',
                'declarationSignature' => 'Declaration Signature',
                'serviceScheme' => 'Service Scheme',
                'advanceAmountRupees' => 'Advance Amount Rupees',
                'commissionAmountRupees' => 'Commission Amount Rupees',
                'commissionAmountInWords' => 'Commission Amount In Words',
                'declaration2Place' => 'Declaration Place',
                'declaration2Date' => 'Declaration Date',
                'declaration2Signature' => 'Declaration Signature 2'
            ];

            $formattedData = [];
            foreach ($data as $key => $value) {
                if (isset($fieldMap[$key])) {
                    $formattedData[$fieldMap[$key]] = $value;
                }
            }

            $pdf = PDF::loadView('pdf.registration_form', [
                'data' => $formattedData,
                'imid' => $data['imid'] ?? 'N/A'
            ]);

            $filename = 'Registration_Form_' . ($data['imid'] ?? 'form') . '.pdf';

            return $pdf->download($filename);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        try {
            $forms = RegistrationForm::with(['user', 'creator'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $forms
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching forms: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $form = RegistrationForm::with(['user', 'creator'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $form
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Form not found'
            ], 404);
        }
    }
}
