<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>INA Dashboard - Client Details</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            animation: fadeInUp 0.8s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            text-align: center;
        }

        .page-title {
            font-size: 36px;
            font-weight: 700;
            background: linear-gradient(135deg, #4a69bd, #5a4fcf);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .profile-id {
            font-size: 18px;
            color: #666;
            font-weight: 500;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .detail-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .detail-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }

        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .card-icon {
            font-size: 24px;
            margin-right: 12px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .service-details { background: linear-gradient(135deg, #A8E6CF, #7FCDCD); }
        .member-info { background: linear-gradient(135deg, #7FCDCD, #48CAE4); }
        .partner-preference { background: linear-gradient(135deg, #FFD93D, #FF8A80); }
        .contact-details { background: linear-gradient(135deg, #48CAE4, #0096C7); }

        .service-details .card-header,
        .member-info .card-header,
        .partner-preference .card-header,
        .contact-details .card-header {
            color: white;
            border-bottom-color: rgba(255, 255, 255, 0.3);
        }

        .service-details .card-title,
        .member-info .card-title,
        .partner-preference .card-title,
        .contact-details .card-title {
            color: white;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
            flex: 1;
        }

        .detail-value {
            color: white;
            font-weight: 600;
            flex: 1;
            text-align: right;
        }

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 120px;
        }

        .btn-back {
            background: linear-gradient(135deg, #A8E6CF, #7FCDCD);
            color: white;
            box-shadow: 0 5px 15px rgba(168, 230, 207, 0.4);
        }

        .btn-back:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(168, 230, 207, 0.6);
            text-decoration: none;
            color: white;
        }

        .btn-prospects {
            background: linear-gradient(135deg, #48CAE4, #0096C7);
            color: white;
            box-shadow: 0 5px 15px rgba(72, 202, 228, 0.4);
        }

        .btn-prospects:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(72, 202, 228, 0.6);
            text-decoration: none;
            color: white;
        }

        @media (max-width: 768px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
            
            .page-title {
                font-size: 28px;
            }
            
            .detail-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
            
            .detail-value {
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="page-title">Client Details - {{ $service->profile_id ?? $service->id ?? 'N/A' }}</div>
            <div class="profile-id">Profile ID: {{ $service->profile_id ?? $service->id ?? 'N/A' }}</div>
        </div>

        <div class="details-grid">
            <!-- Service Details Card -->
            <div class="detail-card service-details">
                <div class="card-header">
                    <span class="card-icon">⚙️</span>
                    <h3 class="card-title">Service Details</h3>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Profile ID:</span>
                    <span class="detail-value">{{ $service->profile_id ?? $service->id ?? 'N/A' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Service Name:</span>
                    <span class="detail-value">{{ $service->service_name ?? $service->name ?? '—' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Price:</span>
                    <span class="detail-value">{{ $service->amount_paid ?? $service->price ?? '0.00' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Success Fee:</span>
                    <span class="detail-value">{{ $service->success_fee ?? '0.00' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Start Date:</span>
                    <span class="detail-value">{{ $service->start_date ? \Carbon\Carbon::parse($service->start_date)->format('d-m-Y') : '—' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Expiry Date:</span>
                    <span class="detail-value">{{ $service->expiry_date ? \Carbon\Carbon::parse($service->expiry_date)->format('d-m-Y') : '—' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Description:</span>
                    <span class="detail-value">{{ $service->service_details ?? $service->description ?? '—' }}</span>
                </div>
            </div>

            <!-- Member Info Card -->
            <div class="detail-card member-info">
                <div class="card-header">
                    <span class="card-icon">👤</span>
                    <h3 class="card-title">Member Info</h3>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value">{{ $service->member_name ?? '—' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Age:</span>
                    <span class="detail-value">{{ $service->member_age ?? '—' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Education:</span>
                    <span class="detail-value">{{ $service->member_education ?? '—' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Occupation:</span>
                    <span class="detail-value">{{ $service->member_occupation ?? '—' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Annual Income:</span>
                    <span class="detail-value">{{ $service->member_income ?? '—' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Marital Status:</span>
                    <span class="detail-value">{{ $service->member_marital_status ?? '—' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Family Status:</span>
                    <span class="detail-value">{{ $service->member_family_status ?? '—' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Father Details:</span>
                    <span class="detail-value">{{ $service->member_father_details ?? '—' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Mother Details:</span>
                    <span class="detail-value">{{ $service->member_mother_details ?? '—' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Sibling Details:</span>
                    <span class="detail-value">{{ $service->member_sibling_details ?? '—' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Caste:</span>
                    <span class="detail-value">{{ $service->member_caste ?? '—' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Subcaste:</span>
                    <span class="detail-value">{{ $service->member_subcaste ?? '—' }}</span>
                </div>
            </div>

            <!-- Partner Preference Card -->
            <div class="detail-card partner-preference">
                <div class="card-header">
                    <span class="card-icon">💕</span>
                    <h3 class="card-title">Partner Preference</h3>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Preferred Age:</span>
                    <span class="detail-value">22 - 27</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Preferred Height:</span>
                    <span class="detail-value">160 - 168</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Preferred Weight:</span>
                    <span class="detail-value">50 - 65</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Preferred Education:</span>
                    <span class="detail-value">ANY</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Preferred Religion:</span>
                    <span class="detail-value">Islam</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Preferred Caste:</span>
                    <span class="detail-value">ANY</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Preferred Sub Caste:</span>
                    <span class="detail-value">ANY</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Preferred Marital Status:</span>
                    <span class="detail-value">Never Married</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Preferred Annual Income:</span>
                    <span class="detail-value">0</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Preferred Occupation:</span>
                    <span class="detail-value">ANY</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Preferred Family Status:</span>
                    <span class="detail-value">Wealthy</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Preferred Eating Habits:</span>
                    <span class="detail-value">ALL</span>
                </div>
            </div>

            <!-- Contact Details Card -->
            <div class="detail-card contact-details">
                <div class="card-header">
                    <span class="card-icon">📞</span>
                    <h3 class="card-title">Contact Details</h3>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Customer Name:</span>
                    <span class="detail-value">{{ $service->contact_customer_name ?? '—' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Mobile No:</span>
                    <span class="detail-value">{{ $service->contact_mobile_no ?? $service->contact_phone ?? '—' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">WhatsApp No:</span>
                    <span class="detail-value">{{ $service->contact_whatsapp_no ?? '—' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">{{ $service->contact_email ?? '—' }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Alternate Contact:</span>
                    <span class="detail-value">+971535151021</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Client:</span>
                    <span class="detail-value">Father</span>
                </div>
            </div>
        </div>

        <div class="button-group">
            <a href="{{ route('view.prospects', $service->profile_id ?? $service->id ?? 0) }}" class="btn btn-prospects">👥 View Prospects</a>
            <a href="{{ route('active.service') }}" class="btn btn-back">← Back to Active Services</a>
        </div>
    </div>

    <script>
        // Add interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Add staggered animation to cards
            const cards = document.querySelectorAll('.detail-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });

            // Add click effect to header
            const header = document.querySelector('.header');
            header.addEventListener('click', function() {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            });
        });
    </script>
</body>
</html>