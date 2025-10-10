<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>INA Dashboard - New Services</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
      color: #333;
      min-height: 100vh;
    }

    /* Main Dashboard Header */
    .main-header {
      background: linear-gradient(135deg, #ac0742, #9d1955);
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 20px rgba(0, 0, 0, 0.2);
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .header-brand {
      color: white;
      font-size: 24px;
      font-weight: 700;
      text-decoration: none;
    }

    .header-nav {
      display: flex;
      list-style: none;
      gap: 25px;
      align-items: center;
    }

    .header-nav li {
      position: relative;
    }

    .header-nav a {
      color: rgba(255, 255, 255, 0.9);
      text-decoration: none;
      font-weight: 500;
      font-size: 15px;
      padding: 10px 16px;
      border-radius: 8px;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .header-nav a:hover {
      color: white;
      background: rgba(255, 255, 255, 0.1);
      transform: translateY(-1px);
    }

    .header-nav a.active {
      color: white;
      background: rgba(255, 255, 255, 0.2);
      font-weight: 600;
    }

    .header-nav .dropdown-arrow {
      font-size: 10px;
      margin-left: 3px;
    }

    .logout-btn {
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.3);
      color: white;
      padding: 8px 16px;
      border-radius: 20px;
      cursor: pointer;
      transition: all 0.3s ease;
      font-weight: 500;
    }

    .logout-btn:hover {
      background: rgba(255, 255, 255, 0.2);
      transform: translateY(-1px);
    }

    /* Main Content Area */
    .main-content {
      padding: 30px;
    }

    .page-title {
      color: #fff;
      font-size: 28px;
      font-weight: 600;
      margin-bottom: 25px;
      text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    /* Add Service Button Styling */
    .add-service-btn-beautiful {
      background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
      color: white;
      border: none;
      padding: 15px 30px;
      border-radius: 50px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
      display: flex;
      align-items: center;
      gap: 10px;
      position: relative;
      overflow: hidden;
    }

.edit-form-scroll-wrapper {
  overflow-y: auto !important;
  max-height: calc(85vh - 180px);
  scrollbar-color: #ac0742 #f8f9fa;
  scrollbar-width: thin;
  padding-right: 8px;
  flex: 1;
}

.edit-form-scroll-wrapper::-webkit-scrollbar {
  width: 8px;
}

.edit-form-scroll-wrapper::-webkit-scrollbar-track {
  background: #f8f9fa;
  border-radius: 10px;
}

.edit-form-scroll-wrapper::-webkit-scrollbar-thumb {
  background: #ac0742;
  border-radius: 10px;
}

.edit-form-scroll-wrapper::-webkit-scrollbar-thumb:hover {
  background: #9d1955;
}

    .add-service-btn-beautiful:before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
      transition: left 0.5s;
    }

    .add-service-btn-beautiful:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 35px rgba(40, 167, 69, 0.6);
    }

    .add-service-btn-beautiful:hover:before {
      left: 100%;
    }

    .add-service-btn-beautiful:active {
      transform: translateY(-1px);
    }

    /* Expired Services Button Styling */
    .expired-services-btn {
      background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
      color: white;
      text-decoration: none;
      padding: 12px 25px;
      border-radius: 50px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 6px 20px rgba(23, 162, 184, 0.4);
      display: flex;
      align-items: center;
      gap: 8px;
      position: relative;
      overflow: hidden;
    }

    .expired-services-btn:before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
      transition: left 0.5s;
    }

    .expired-services-btn:hover {
      color: white;
      text-decoration: none;
      transform: translateY(-2px);
      box-shadow: 0 10px 30px rgba(23, 162, 184, 0.6);
    }

    .expired-services-btn:hover:before {
      left: 100%;
    }

    .expired-services-btn:active {
      transform: translateY(-1px);
    }

    /* Modal Overlay */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      backdrop-filter: blur(5px);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 2000;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
    }

    .modal-overlay.active {
      opacity: 1;
      visibility: visible;
    }

    /* Modal (for delete confirmation modals) */
    .modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      backdrop-filter: blur(5px);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 2000;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease;
    }

    .modal.active {
      opacity: 1;
      visibility: visible;
    }

    .modal .modal-content {
      background: white;
      padding: 30px;
      border-radius: 15px;
      width: 90%;
      max-width: 600px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      transform: translateY(-30px) scale(0.95);
      transition: all 0.3s ease;
      position: relative;
    }

    .modal.active .modal-content {
      transform: translateY(0) scale(1);
    }

    /* Modal Content */
    .modal-content-beautiful {
      background: white;
      padding: 40px;
      border-radius: 20px;
      width: 90%;
      max-width: 700px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      transform: translateY(-30px) scale(0.95);
      transition: all 0.3s ease;
      position: relative;
    }

    .modal-overlay.active .modal-content-beautiful {
      transform: translateY(0) scale(1);
    }

    .modal-content-beautiful h2 {
      color: #2c3e50;
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 30px;
      text-align: center;
      position: relative;
      padding-bottom: 15px;
    }

    .modal-content-beautiful h2:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 3px;
      background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
      border-radius: 2px;
    }

    /* Form Styling */
    .modal-form-row {
      display: flex;
      gap: 20px;
      margin-bottom: 25px;
    }

    .modal-form-group {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .modal-form-group label {
      color: #34495e;
      font-weight: 600;
      margin-bottom: 8px;
      font-size: 14px;
      letter-spacing: 0.3px;
    }

    .modal-form-group input,
    .modal-form-group select {
      padding: 15px;
      border: 2px solid #e8ecef;
      border-radius: 12px;
      font-size: 15px;
      transition: all 0.3s ease;
      background: #f8f9fa;
      color: #2c3e50;
    }

    .modal-form-group input:focus,
    .modal-form-group select:focus {
      outline: none;
      border-color: #ac0742;
      background: white;
      box-shadow: 0 0 0 3px rgba(172, 7, 66, 0.1);
      transform: translateY(-1px);
    }

    .modal-form-group select {
      cursor: pointer;
    }

    /* Modal Actions */
    .modal-actions {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-top: 35px;
      padding-top: 25px;
      border-top: 1px solid #e8ecef;
    }

    .btn {
      padding: 15px 30px;
      border: none;
      border-radius: 50px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      min-width: 120px;
      position: relative;
      overflow: hidden;
    }

    .btn-primary {
      background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
      color: white;
      box-shadow: 0 5px 15px rgba(172, 7, 66, 0.4);
    }

    .btn-primary:before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
      transition: left 0.5s;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
    }

    .btn-primary:hover:before {
      left: 100%;
    }

    .btn-secondary {
      background: #95a5a6;
      color: white;
      box-shadow: 0 5px 15px rgba(149, 165, 166, 0.4);
    }

    .btn-secondary:hover {
      background: #7f8c8d;
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(149, 165, 166, 0.6);
    }

    /* Table Container */
    .table-container {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 15px;
      padding: 30px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      margin: 0 auto;
      /* overflow-x removed to allow scroll wrapper to work */
    }

    .table-title {
      color: #2c3e50;
      font-size: 24px;
      font-weight: 600;
      margin-bottom: 25px;
      text-align: center;
      border-bottom: 2px solid #e0e0e0;
      padding-bottom: 15px;
    }

    /* Table Styling */
    .services-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    .services-table th {
      background: linear-gradient(135deg, #ac0742, #9d1955);
      color: white;
      text-align: left;
      padding: 15px;
      font-weight: 600;
      border-right: 1px solid rgba(255, 255, 255, 0.2);
    }

    .services-table th:last-child {
      border-right: none;
    }

    .services-table td {
      padding: 15px;
      border-bottom: 1px solid #e0e0e0;
      color: #2c3e50;
    }

    .services-table tr:nth-child(even) {
      background-color: rgba(74, 105, 189, 0.05);
    }

    .services-table tr:hover {
      background-color: rgba(74, 105, 189, 0.1);
    }

    /* Serial Number column styling */
    .services-table th:first-child,
    .services-table td:first-child {
      width: 60px;
      text-align: center;
      font-weight: 600;
    }

    .services-table td:first-child {
      color: #ac0742;
      font-size: 14px;
    }

    /* Column width adjustments */
    .services-table th:nth-child(1),
    .services-table td:nth-child(1) {
      width: 60px; /* Sl No */
    }

    .services-table th:nth-child(2),
    .services-table td:nth-child(2) {
      width: 120px; /* Profile ID */
    }

    .services-table th:nth-child(3),
    .services-table td:nth-child(3) {
      width: 200px; /* Name - increased length */
    }

    .services-table th:nth-child(4),
    .services-table td:nth-child(4) {
      width: 100px; /* Plan Name - reduced */
    }

    .services-table th:nth-child(5),
    .services-table td:nth-child(5) {
      width: 100px; /* Payment Date - reduced */
    }

    .services-table th:nth-child(6),
    .services-table td:nth-child(6) {
      width: 110px; /* RM Name - reduced */
    }

    .services-table th:nth-child(7),
    .services-table td:nth-child(7) {
      width: 140px; /* Actions - reduced */
    }

    /* Text handling for smaller columns */
    .services-table td:nth-child(4),
    .services-table td:nth-child(5),
    .services-table td:nth-child(6) {
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      font-size: 13px;
    }

    /* Name column - allow wrapping for longer names */
    .services-table td:nth-child(3) {
      word-wrap: break-word;
      line-height: 1.4;
    }

    /* Edit column styling (last column for admin) */
    .services-table th:last-child,
    .services-table td:last-child {
      width: 80px;
      text-align: center;
    }

    .action-link {
      color: #ac0742;
      text-decoration: none;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
    }

    .action-link:before {
      content: "○";
      margin-right: 5px;
      font-size: 10px;
      color: #9d1955;
    }

    .action-link:hover {
      color: #9d1955;
      text-decoration: underline;
    }

    /* Action buttons container */
    .action-buttons {
      display: flex;
      gap: 10px;
      align-items: center;
      flex-wrap: wrap;
    }

    /* Edit button styles */
    .edit-btn {
      background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 12px;
      cursor: pointer;
      font-size: 11px;
      font-weight: 500;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 3px;
    }

    .edit-btn:hover {
      background: linear-gradient(135deg, #9d1955 0%, #ac0742 100%);
      transform: translateY(-1px);
      box-shadow: 0 3px 10px rgba(172, 7, 66, 0.3);
    }

    .edit-btn svg {
      margin-right: 3px;
    }

    /* Delete button styles */
    .delete-btn {
      background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 12px;
      cursor: pointer;
      font-size: 11px;
      font-weight: 500;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 3px;
    }

    .delete-btn:hover {
      background: linear-gradient(135deg, #c82333 0%, #dc3545 100%);
      transform: translateY(-1px);
      box-shadow: 0 3px 10px rgba(220, 53, 69, 0.3);
    }

    .delete-btn svg {
      margin-right: 3px;
    }

    /* Status tracking button styles */
    .status-tracking-btn {
      background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 12px;
      cursor: pointer;
      font-size: 11px;
      font-weight: 500;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 3px;
    }

    .status-tracking-btn:hover {
      background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
      transform: translateY(-1px);
      box-shadow: 0 3px 10px rgba(23, 162, 184, 0.3);
    }

    .status-tracking-btn svg {
      margin-right: 3px;
    }

    .no-access {
      color: #dc3545;
      font-style: italic;
      font-size: 12px;
      font-weight: 500;
    }

    /* Search Functionality Styles */
    .search-container:focus-within {
      border-color: #ac0742 !important;
      box-shadow: 0 4px 15px rgba(172, 7, 66, 0.2) !important;
      transform: translateY(-1px);
    }

    .search-input::placeholder {
      color: #999;
      font-style: italic;
    }

    .search-btn:hover {
      background: linear-gradient(135deg, #9d1955 0%, #ac0742 100%) !important;
      transform: translateY(-1px);
      box-shadow: 0 5px 15px rgba(172, 7, 66, 0.3);
    }

    .clear-search:hover {
      background: #7f8c8d !important;
      color: white !important;
      text-decoration: none !important;
    }

    /* Responsive design */
    @media (max-width: 768px) {
      .main-content {
        padding: 20px;
      }
      
      .table-container {
        padding: 20px;
      }
      
      .services-table {
        font-size: 14px;
      }
      
      .services-table th,
      .services-table td {
        padding: 10px;
      }

      /* Adjust Sl No column for mobile */
      .services-table th:first-child,
      .services-table td:first-child {
        width: 40px;
        font-size: 12px;
      }

      .modal-content-beautiful {
        padding: 25px;
        margin: 20px;
      }

      .modal-form-row {
        flex-direction: column;
        gap: 15px;
      }

      .add-service-btn-beautiful {
        padding: 12px 24px;
        font-size: 14px;
      }
    }

    /* Custom Pagination Styles */
    .pagination-wrapper {
      margin-top: 40px;
      padding: 25px 0;
      border-top: 2px solid #f0f0f0;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 20px;
    }

    .pagination-info {
      display: flex;
      align-items: center;
    }

    .pagination-text {
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      color: #495057;
      padding: 10px 16px;
      border-radius: 25px;
      font-size: 14px;
      font-weight: 500;
      border: 1px solid #dee2e6;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .custom-pagination {
      display: flex;
      align-items: center;
    }

    .pagination-container {
      display: flex;
      align-items: center;
      gap: 8px;
      background: white;
      padding: 8px;
      border-radius: 50px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      border: 1px solid #e9ecef;
    }

    .pagination-btn {
      display: flex;
      align-items: center;
      gap: 6px;
      padding: 10px 16px;
      border-radius: 25px;
      text-decoration: none;
      font-weight: 600;
      font-size: 14px;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
      position: relative;
      overflow: hidden;
    }

    .pagination-btn-active {
      background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
      color: white;
      box-shadow: 0 4px 8px rgba(172, 7, 66, 0.3);
    }

    .pagination-btn-active:hover {
      background: linear-gradient(135deg, #9d1955 0%, #ac0742 100%);
      transform: translateY(-1px);
      box-shadow: 0 6px 12px rgba(172, 7, 66, 0.4);
      color: white;
      text-decoration: none;
    }

    .pagination-btn-disabled {
      background: #f8f9fa;
      color: #adb5bd;
      cursor: not-allowed;
    }

    .pagination-icon {
      font-size: 16px;
      font-weight: bold;
    }

    .pagination-numbers {
      display: flex;
      align-items: center;
      gap: 4px;
      margin: 0 8px;
    }

    .pagination-number {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      text-decoration: none;
      font-weight: 600;
      font-size: 14px;
      transition: all 0.3s ease;
      background: #f8f9fa;
      color: #495057;
      border: 1px solid #dee2e6;
      position: relative;
    }

    .pagination-number:hover {
      background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
      color: white;
      transform: scale(1.1);
      box-shadow: 0 4px 8px rgba(172, 7, 66, 0.3);
      text-decoration: none;
    }

    .pagination-number-current {
      background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
      color: white;
      box-shadow: 0 4px 8px rgba(172, 7, 66, 0.4);
      transform: scale(1.05);
    }

    /* Responsive Pagination */
    @media (max-width: 768px) {
      .pagination-wrapper {
        flex-direction: column;
        text-align: center;
        gap: 15px;
      }
      
      .pagination-container {
        padding: 6px;
        gap: 4px;
      }
      
      .pagination-btn {
        padding: 8px 12px;
        font-size: 12px;
      }
      
      .pagination-btn span {
        display: none;
      }
      
      .pagination-number {
        width: 35px;
        height: 35px;
        font-size: 12px;
      }
      
      .pagination-text {
        padding: 8px 12px;
        font-size: 12px;
      }
    }

    /* Pagination Animation Effects */
    .pagination-container {
      animation: fadeInUp 0.5s ease-out;
    }

    .pagination-btn-active::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
      transition: left 0.5s;
    }

    .pagination-btn-active:hover::before {
      left: 100%;
    }

    .pagination-number::after {
      content: '';
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      border-radius: 50%;
      background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%);
      opacity: 0;
      transform: scale(0.8);
      transition: all 0.3s ease;
      z-index: -1;
    }

    .pagination-number:hover::after {
      opacity: 1;
      transform: scale(1);
    }

    /* Pulse animation for current page */
    .pagination-number-current {
      animation: pulse 2s infinite;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes pulse {
      0% {
        box-shadow: 0 4px 8px rgba(172, 7, 66, 0.4);
      }
      50% {
        box-shadow: 0 4px 8px rgba(172, 7, 66, 0.7), 0 0 0 8px rgba(172, 7, 66, 0.1);
      }
      100% {
        box-shadow: 0 4px 8px rgba(172, 7, 66, 0.4);
      }
    }

    /* Per page dropdown styling */
    #perPageSelect {
      background-color: white;
      border: 1px solid #ddd;
      border-radius: 4px;
      padding: 6px 12px;
      font-size: 14px;
      color: #495057;
      cursor: pointer;
      transition: border-color 0.15s ease-in-out;
    }

    #perPageSelect:focus {
      outline: none;
      border-color: #ac0742;
      box-shadow: 0 0 0 0.2rem rgba(172, 7, 66, 0.25);
    }
  </style>
</head>
<body>

  <!-- Main Dashboard Header -->
  <header class="main-header">
    <a href="#" class="header-brand">INA</a>
    <nav>
      <ul class="header-nav">
        <li><a href="{{ route('dashboard') }}">Home</a></li>
        <li><a href="{{ route('profile.hellow') }}">Profiles</a></li>
        <li><a href="#">Sales <span class="dropdown-arrow">▼</span></a></li>
        <li><a href="#">HelpLine</a></li>
        <li><a href="{{ route('fresh.data') }}">Fresh Data <span class="dropdown-arrow">▼</span></a></li>
        <li><a href="#">abc</a></li>
        <li><a href="{{ route('services.page') }}" class="active">Services <span class="dropdown-arrow">▼</span></a></li>
      </ul>
    </nav>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
      @csrf
    </form>
    <button class="logout-btn" id="logout-btn">Logout</button>
  </header>

  <!-- Main Content Area -->
  <main class="main-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
      <h1 class="page-title">New Services</h1>
      @if(Auth::check() && Auth::user()->is_admin)
        <div style="display: flex; gap: 15px; align-items: center;">
          <a href="{{ route('expired.services') }}" class="expired-services-btn">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
              <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
              <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
            </svg>
            Expired Services
          </a>
          <button id="add-new-service-btn" class="add-service-btn-beautiful">
            <span>✨</span> Add New Service
          </button>
        </div>
      @endif
    </div>

    @if(Auth::check() && Auth::user()->is_admin)
    <!-- Add New Service Modal -->
    <div id="add-service-modal" class="modal-overlay">
      <div class="modal-content-beautiful">
        <h2>Add New Service</h2>
        <form id="add-service-form-modal" method="POST" action="{{ route('new.service.store') }}">
          @csrf
          <div class="modal-form-row">
            <div class="modal-form-group">
              <label>Profile ID</label>
              <input type="text" name="profile_id" placeholder="Enter Profile ID" required>
            </div>
            <div class="modal-form-group">
              <label>Member Name</label>
              <input type="text" name="member_name" placeholder="Enter member name" required>
            </div>
          </div>
          <div class="modal-form-row">
            <div class="modal-form-group">
              <label>Gender</label>
              <select name="member_gender" required>
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div class="modal-form-group">
              <label>Mobile Number</label>
              <input type="tel" name="contact_mobile_no" placeholder="Enter mobile number" required>
            </div>
            <div class="modal-form-group">
              <label>Alternative Contact Number</label>
              <input type="tel" name="contact_alternate" placeholder="Enter alternative contact number">
            </div>
          </div>
          <div class="modal-form-row">
            <div class="modal-form-group">
              <label>Service Executive</label>
              <select name="service_executive" required>
                <option value="">Select Service Executive</option>
                @if(isset($staffUsers))
                  @foreach($staffUsers as $staff)
                    <option value="{{ $staff->first_name }}" 
                            {{ (Auth::user()->first_name == $staff->first_name) ? 'selected' : '' }}>
                      {{ $staff->first_name }}
                    </option>
                  @endforeach
                @else
                  <option value="{{ Auth::user()->first_name ?? 'admin' }}" selected>
                    {{ Auth::user()->first_name ?? 'admin' }}
                  </option>
                @endif
              </select>
            </div>
          </div>
          <div class="modal-actions">
            <button type="button" id="close-modal-btn" class="btn btn-secondary">Cancel</button>
            <button type="submit" class="btn btn-primary">Add Service</button>
          </div>
        </form>
      </div>
    </div>
    @endif

    @if(Auth::check() && Auth::user()->is_admin)
   
<!-- Edit Service Modal -->
    <div id="edit-modal-overlay" class="modal-overlay">
     <div class="modal-content-beautiful">
        <h2>Edit Service</h2>
        <form id="edit-service-form" method="POST" action="">
          @csrf
          @method('PUT')
          
          <div class="edit-form-scroll-wrapper">
            <div class="modal-form-row">
              <div class="modal-form-group">
                <label>Profile ID</label>
                <input type="text" name="profile_id" id="edit_profile_id" placeholder="Enter Profile ID" required>
              </div>
              <div class="modal-form-group">
                <label>Member Name</label>
                <input type="text" name="name" id="edit_name" placeholder="Enter member name" required>
              </div>
            </div>
            <div class="modal-form-row">
              <div class="modal-form-group">
                <label>Gender</label>
                <select name="member_gender" id="edit_gender">
                  <option value="">Select Gender</option>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                  <option value="other">Other</option>
                </select>
              </div>
              <div class="modal-form-group">
                <label>Mobile Number</label>
                <input type="tel" name="contact_mobile_no" id="edit_mobile" placeholder="Enter mobile number" required>
              </div>
              <div class="modal-form-group">
                <label>Alternative Contact Number</label>
                <input type="tel" name="contact_alternate" id="edit_contact_alternate" placeholder="Enter alternative contact number">
              </div>
            </div>
            <div class="modal-form-row">
              <div class="modal-form-group">
                <label>Service Executive</label>
                @if(Auth::check() && Auth::user()->is_admin)
                  <select name="service_executive" id="edit_service_executive" required style="display: block;">
                    <option value="">Select Service Executive</option>
                    @if(isset($staffUsers))
                      @foreach($staffUsers as $staff)
                        <option value="{{ $staff->first_name }}">{{ $staff->first_name }}</option>
                      @endforeach
                    @endif
                  </select>
                  <input type="text" id="edit_service_executive_display" readonly style="background-color: #f5f5f5; cursor: not-allowed; display: none;" placeholder="Current Service Executive (Changed via RM)">
                @else
                  <input type="text" id="edit_service_executive_readonly" readonly style="background-color: #f8f9fa; cursor: not-allowed; border: 1px solid #dee2e6; padding: 8px; border-radius: 4px; color: #6c757d;" placeholder="Service Executive (Admin Only)">
                  <small style="color: #6c757d; font-size: 12px;">Only administrators can modify service executive assignments</small>
                @endif
              </div>
              @if(Auth::check() && Auth::user()->is_admin)
              <div class="modal-form-group">
                <label>RM Change</label>
                <select name="rm_change" id="edit_rm_change">
                  <option value="">Select RM to Change</option>
                  @if(isset($staffUsers))
                    @foreach($staffUsers as $staff)
                      <option value="{{ $staff->first_name }}">{{ $staff->first_name }}</option>
                    @endforeach
                  @endif
                </select>
                <small style="color: #666; font-size: 12px;">Select this to change the current service executive</small>
                
                <!-- RM Change History Box -->
                <div id="rm_history_box" style="display: none; margin-top: 10px; padding: 12px; background-color: #d4edda; border: 2px solid #28a745; border-radius: 6px;">
                  <div id="rm_history_content" style="color: #dc3545; font-size: 13px; font-weight: 500; line-height: 1.4;">
                    <!-- History will be populated here -->
                  </div>
                </div>
              </div>
              @endif
            </div>
            <div class="modal-form-row">
              <div class="modal-form-group" style="width: 100%;">
                <label>Comment (Required for editing) <span style="color: red;">*</span></label>
                <textarea name="edit_comment" id="edit_comment" placeholder="Please enter a comment explaining the reason for this edit..." required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; min-height: 80px; font-family: inherit; resize: vertical;"></textarea>
                <small style="color: #666; font-size: 12px;">This field is mandatory. Please explain why you are making this edit.</small>
              </div>
            </div>
            <div class="modal-form-row">
              <div class="modal-form-group" style="width: 50%;">
                <label>Status</label>
                <select name="status" id="edit_status" style="width: 100%; max-width: 200px;">
                  <option value="" selected>Select Status</option>
                  <option value="postponed">Postponed</option>
                  <option value="deleted">Deleted</option>
                  <option value="RM changed">RM Changed</option>
                </select>
              </div>
            </div>
          </div>

          <div class="modal-actions">
            <button type="button" id="close-edit-modal-btn" class="btn btn-secondary">Cancel</button>
            <button type="submit" class="btn btn-primary">Update Service</button>
          </div>
        </form>
      </div>
    </div>
    @endif
    <!-- Delete Confirmation Modal -->
    <div id="delete-confirmation-modal" class="modal">
      <div class="modal-content" style="max-width: 500px;">
        <h2 style="color: #dc3545;">Confirm Deletion</h2>
        <div class="modal-form">
          <p style="margin-bottom: 20px; font-size: 16px; line-height: 1.5;">
            Are you sure you want to delete this service?<br>
            <strong style="color: #dc3545;">This action cannot be undone.</strong>
          </p>
          <div class="modal-actions" style="gap: 10px;">
            <button type="button" id="cancel-delete-btn" class="btn btn-secondary">No, Cancel</button>
            <button type="button" id="confirm-delete-btn" class="btn" style="background: #dc3545; color: white;">Yes, Delete</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Comment Modal -->
    <div id="delete-comment-modal" class="modal">
      <div class="modal-content" style="max-width: 600px;">
        <h2 style="color: #dc3545;">Reason for Deletion</h2>
        <form id="delete-service-form" method="POST" action="">
          @csrf
          @method('DELETE')
          <div class="modal-form">
            <div class="modal-form-row">
              <div class="modal-form-group" style="width: 100%;">
                <label>Comment (Required for deletion) <span style="color: red;">*</span></label>
                <textarea name="delete_comment" id="delete_comment" placeholder="Please enter a reason for deleting this service..." required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; min-height: 100px; font-family: inherit; resize: vertical;"></textarea>
                <small style="color: #666; font-size: 12px;">This field is mandatory. Please explain why you are deleting this service.</small>
              </div>
            </div>
            <div class="modal-actions">
              <button type="button" id="cancel-delete-comment-btn" class="btn btn-secondary">Cancel</button>
              <button type="submit" class="btn" style="background: #dc3545; color: white;">Delete Service</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Status Tracking Modal -->
    <div id="status-tracking-modal" class="modal">
      <div class="modal-content" style="max-width: 900px; position: relative;">
        <button id="close-status-modal-btn" style="position: absolute; top: 18px; right: 18px; background: none; border: none; font-size: 22px; color: #888; cursor: pointer; z-index: 10;" title="Close">&times;</button>
        <h2 style="color: #17a2b8; margin-bottom: 20px;">📋 Service Details & Status</h2>
        
        <!-- Status History Table -->
        <div style="margin-bottom: 25px;">
          <table id="status-history-table" style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <thead>
              <tr style="background-color: #f8f9fa;">
                <th style="padding: 12px; border: 1px solid #ddd; font-weight: bold; text-align: left; width: 20%;">Date</th>
                <th style="padding: 12px; border: 1px solid #ddd; font-weight: bold; text-align: left; width: 15%;">Status</th>
                <th style="padding: 12px; border: 1px solid #ddd; font-weight: bold; text-align: left; width: 45%;">Comment</th>
                <th style="padding: 12px; border: 1px solid #ddd; font-weight: bold; text-align: left; width: 20%;">Updated By</th>
              </tr>
            </thead>
            <tbody id="status-history-body">
              <tr>
                <td colspan="4" style="padding: 20px; text-align: center; color: #666; font-style: italic; border: 1px solid #ddd;">
                  No status history found for this service
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Status Update Form Removed as per request -->
        </form>
      </div>
    </div>

    <div class="table-container">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 class="table-title">List of New Services
          @if(isset($services) && method_exists($services, 'total'))
          <span style="font-size: 14px; font-weight: normal; color: #666;">
            ({{ $services->firstItem() ?? 0 }} - {{ $services->lastItem() ?? 0 }} of {{ $services->total() }} services)
          </span>
          @endif
        </h2>
        
        <!-- Right side controls: Entries per page dropdown and Search -->
        <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 10px;">
          <!-- Entries per page dropdown -->
          <div style="display: flex; align-items: center; gap: 10px;">
            <label for="perPageSelect" style="font-size: 14px; color: #666;">Show:</label>
            <select id="perPageSelect" onchange="changePerPage()" style="padding: 5px 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px;">
              <option value="10" {{ (isset($perPage) && $perPage == 10) ? 'selected' : '' }}>10 entries</option>
              <option value="50" {{ (isset($perPage) && $perPage == 50) ? 'selected' : '' }}>50 entries</option>
              <option value="100" {{ (isset($perPage) && $perPage == 100) ? 'selected' : '' }}>100 entries</option>
            </select>
          </div>
          
          <!-- Search Controls - Positioned below Show entries -->
          <div class="search-controls" style="display: flex; align-items: center; gap: 10px;">
            <form method="GET" action="{{ route('new.service') }}" style="display: flex; align-items: center; gap: 10px;">
              <div class="search-container" style="display: flex; align-items: center; gap: 6px; background: white; padding: 6px 12px; border-radius: 20px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); border: 1px solid #e1e5e9; transition: all 0.3s ease;">
                <input type="text" name="search" class="search-input" placeholder="Search services..." value="{{ $search ?? '' }}" style="border: none; outline: none; padding: 4px 8px; font-size: 13px; width: 200px; background: transparent; color: #333;">
                <button type="submit" class="search-btn" style="background: linear-gradient(135deg, #ac0742 0%, #9d1955 100%); color: white; border: none; padding: 6px 10px; border-radius: 15px; cursor: pointer; font-size: 12px; font-weight: 500; transition: all 0.3s ease; display: flex; align-items: center; gap: 3px;">
                  <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                  </svg>
                  Go
                </button>
              </div>
              
              @if($search ?? '')
              <a href="{{ route('new.service') }}?per_page={{ $perPage }}" class="clear-search" style="background: #95a5a6; color: white; border: none; padding: 4px 8px; border-radius: 12px; cursor: pointer; font-size: 11px; text-decoration: none; transition: all 0.3s ease;">Clear</a>
              @endif
              
              <!-- Hidden field to preserve per_page selection -->
              <input type="hidden" name="per_page" value="{{ $perPage }}">
            </form>
          </div>
        </div>
      </div>
      
  <div class="services-table-scroll-wrapper">
    <table class="services-table" style="width: 100%;">
        <thead>
          <tr>
            <th>Sl No</th>
            <th>Profile ID</th>
            <th>Name</th>
            <th>Plan Name</th>
            <th>Payment Date</th>
            <th>RM Name</th>
            <th>Actions</th>
            @if(Auth::check() && Auth::user()->is_admin)
            <th>Edit</th>
            @endif
          </tr>
        </thead>
        <tbody id="services-tbody">
        @if(isset($services) && count($services))
            @foreach($services as $index => $service)
            <tr>
                <td>{{ ($services->currentPage() - 1) * $services->perPage() + $index + 1 }}</td>
                <td>{{ $service->profile_id }}</td>
                <td>{{ $service->name }}</td>
                <td>{{ $service->plan_name }}</td>
                <td>{{ $service->payment_date ? \Carbon\Carbon::parse($service->payment_date)->format('d-M-Y') : '' }}</td>
                <td>
                  @if($service->rm_change_history)
                    @php
                      $history = json_decode($service->rm_change_history, true);
                      $changesCount = is_array($history) ? count($history) : 0;
                    @endphp
                    @if($changesCount > 0)
                      @php
                        $lastChange = $history[$changesCount-1];
                        $currentRM = $lastChange['to'] ?? $service->service_executive; // Show the last changed "to" RM
                        $allChanges = '';
                        foreach($history as $i => $change) {
                          $allChanges .= ($i+1) . ". " . ($change['from'] ?? 'Unknown') . " → " . ($change['to'] ?? 'Unknown') . " (by " . ($change['changed_by'] ?? 'Unknown') . " on " . date('d-M-Y', strtotime($change['changed_at'] ?? now())) . ")\n";
                        }
                      @endphp
                      <span title="{{ trim($allChanges) }}" style="cursor: help; font-size: 16px; font-weight: bold;">
                        {{ $currentRM }}
                      </span>
                    @else
                      <span style="font-size: 16px; font-weight: bold;">{{ $service->service_executive }}</span>
                    @endif
                  @else
                    <span style="font-size: 16px; font-weight: bold;">{{ $service->service_executive }}</span>
                  @endif
                </td>
                <td>
                  <a href="{{ route('service.details', ['id' => $service->profile_id, 'name' => $service->name]) }}" class="action-link">Service Details</a>
                </td>
                @if(Auth::check() && (Auth::user()->is_admin || Auth::user()->user_type === 'staff'))
                <td>
                  <div style="display: flex; gap: 5px; justify-content: center;">
                    @if(Auth::user()->is_admin)
                    <button class="edit-btn" data-service-id="{{ $service->id }}">
                      <svg width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708L10.5 8.207l-3-3L12.146.146zM11.207 9l-3-3L2.5 11.707V13.5h1.793L11.207 9z"/>
                      </svg>
                      Edit
                    </button>
                    @endif
                    <button class="status-tracking-btn" data-service-id="{{ $service->id }}">
                      <svg width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                        <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.697 19.697 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.188-.012.396-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.066.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.712 5.712 0 0 1-.911-.95 11.651 11.651 0 0 0-1.997.406 11.307 11.307 0 0 1-1.02 1.51c-.292.35-.609.656-.927.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.266.266 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.71 12.71 0 0 1 1.01-.193 11.744 11.744 0 0 1-.51-.858 20.801 20.801 0 0 1-.5 1.05zm2.446.45c.15.163.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.876 3.876 0 0 0-.612-.053zM8.078 7.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.135.468z"/>
                      </svg>
                      Status
                    </button>
                    <button class="delete-btn" data-service-id="{{ $service->id }}">
                      <svg width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                      </svg>
                      Delete
                    </button>
                  </div>
                </td>
                @endif
            </tr>
            @endforeach
        @else
            <tr><td colspan="{{ Auth::check() && Auth::user()->is_admin ? '8' : '7' }}" style="text-align:center;">No services found.</td></tr>
        @endif
        </tbody>
    </table>
  </div>
      
      <!-- Custom Designed Pagination -->
      @if(isset($services) && method_exists($services, 'links'))
      <div class="pagination-wrapper">
        <!-- Pagination info -->
        <div class="pagination-info">
          <span class="pagination-text">Showing {{ $services->firstItem() ?? 0 }} to {{ $services->lastItem() ?? 0 }} of {{ $services->total() }} results</span>
        </div>
        
        <!-- Custom Pagination Navigation -->
        <div class="custom-pagination">
          @if ($services->hasPages())
            <div class="pagination-container">
              {{-- Previous Page Link --}}
              @if ($services->onFirstPage())
                <span class="pagination-btn pagination-btn-disabled">
                  <i class="pagination-icon">‹</i>
                  <span>Previous</span>
                </span>
              @else
                <a href="{{ $services->previousPageUrl() }}" class="pagination-btn pagination-btn-active">
                  <i class="pagination-icon">‹</i>
                  <span>Previous</span>
                </a>
              @endif

              {{-- Pagination Elements --}}
              <div class="pagination-numbers">
                @foreach ($services->getUrlRange(1, $services->lastPage()) as $page => $url)
                  @if ($page == $services->currentPage())
                    <span class="pagination-number pagination-number-current">{{ $page }}</span>
                  @else
                    <a href="{{ $url }}" class="pagination-number">{{ $page }}</a>
                  @endif
                @endforeach
              </div>

              {{-- Next Page Link --}}
              @if ($services->hasMorePages())
                <a href="{{ $services->nextPageUrl() }}" class="pagination-btn pagination-btn-active">
                  <span>Next</span>
                  <i class="pagination-icon">›</i>
                </a>
              @else
                <span class="pagination-btn pagination-btn-disabled">
                  <span>Next</span>
                  <i class="pagination-icon">›</i>
                </span>
              @endif
            </div>
          @endif
        </div>
      </div>
      @endif
    </div>
  </main>

  <script>
    // Function to change per page count - global function
    function changePerPage() {
      const select = document.getElementById('perPageSelect');
      const perPage = select.value;
      const url = new URL(window.location.href);
      url.searchParams.set('per_page', perPage);
      url.searchParams.delete('page'); // Reset to page 1 when changing per page
      // Preserve search parameter if it exists
      const searchInput = document.querySelector('input[name="search"]');
      if (searchInput && searchInput.value) {
        url.searchParams.set('search', searchInput.value);
      }
      window.location.href = url.toString();
    }

    // Logout functionality
    document.addEventListener('DOMContentLoaded', function() {
      var logoutBtn = document.getElementById('logout-btn');
      if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
          e.preventDefault();
          if(confirm('Are you sure you want to logout?')) {
            document.getElementById('logout-form').submit();
          }
        });
      }
    });

    // Modal logic for Add New Service
    document.addEventListener('DOMContentLoaded', function() {
      const addNewServiceBtn = document.getElementById('add-new-service-btn');
      const addServiceModal = document.getElementById('add-service-modal');
      const closeModalBtn = document.getElementById('close-modal-btn');
      const serviceForm = document.getElementById('add-service-form-modal');

      // Open modal
      if (addNewServiceBtn && addServiceModal) {
        addNewServiceBtn.addEventListener('click', function(e) {
          e.preventDefault();
          addServiceModal.classList.add('active');
          document.body.style.overflow = 'hidden';
        });
      }

      // Close modal
      if (closeModalBtn && addServiceModal) {
        closeModalBtn.addEventListener('click', function(e) {
          e.preventDefault();
          addServiceModal.classList.remove('active');
          document.body.style.overflow = 'auto';
        });
      }

      // Submit add service form
      if (serviceForm) {
        serviceForm.addEventListener('submit', function(e) {
          e.preventDefault();
          const formData = new FormData(this);
          fetch('/new-service', {
            method: 'POST',
            body: formData,
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              showNotification('Service added successfully!', 'success');
              addServiceModal.classList.remove('active');
              document.body.style.overflow = 'auto';
              setTimeout(() => window.location.reload(), 1000);
            } else {
              showNotification(data.message || 'Failed to add service', 'error');
            }
          })
          .catch(() => {
            showNotification('Failed to add service', 'error');
          });
        });
      }
    });
    // (Removed duplicate .catch and }); lines that caused JS errors)

    // Notification function
    function showNotification(message, type = 'info') {
      const notification = document.createElement('div');
      notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        background: ${type === 'success' ? '#2ecc71' : '#3498db'};
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 3000;
        transform: translateX(300px);
        transition: transform 0.3s ease;
        font-weight: 500;
      `;
      notification.textContent = message;
      document.body.appendChild(notification);

      // Animate in
      setTimeout(() => {
        notification.style.transform = 'translateX(0)';
      }, 100);

      // Remove after 3 seconds
      setTimeout(() => {
        notification.style.transform = 'translateX(300px)';
        setTimeout(() => {
          document.body.removeChild(notification);
        }, 300);
      }, 3000);
    }

    // Main header navigation
    document.querySelectorAll('.header-nav a').forEach(link => {
      link.addEventListener('click', function(e) {
        if (!this.getAttribute('href') || this.getAttribute('href') === '#') {
          e.preventDefault();
          document.querySelectorAll('.header-nav a').forEach(l => l.classList.remove('active'));
          this.classList.add('active');
          const page = this.getAttribute('data-page');
        }
      });
    });

    // Logout functionality
    document.querySelector('.logout-btn').addEventListener('click', function() {
      if(confirm('Are you sure you want to logout?')) {
        showNotification('Logging out...', 'info');
        setTimeout(() => {
          // Redirect to login page
        }, 1500);
      }
    });

    // Edit Modal Functionality - Only for Admins
    const editModal = document.getElementById('edit-modal-overlay');
    const editForm = document.getElementById('edit-service-form');
    const closeEditModalBtn = document.getElementById('close-edit-modal-btn');
    
    // Only proceed if edit modal elements exist (admin user)
    if (!editModal || !editForm || !closeEditModalBtn) {
      // Don't return here, still set up basic click handlers for debugging
    }
    
    // Check for edit buttons on page and add direct listeners
    const editButtons = document.querySelectorAll('.edit-btn');
    
    // Add direct click listeners to each edit button
    editButtons.forEach((button, index) => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const serviceId = this.getAttribute('data-service-id');
        
        if (serviceId) {
          openEditModal(serviceId);
        }
      });
    });
    
    // Add event listeners to all edit buttons using event delegation
    document.addEventListener('click', function(e) {
      // Find the edit button, even if clicked on child elements (SVG, path, text)
      let editBtn = null;
      
      if (e.target.classList.contains('edit-btn')) {
        editBtn = e.target;
      } else if (e.target.closest('.edit-btn')) {
        editBtn = e.target.closest('.edit-btn');
      }
      
      if (editBtn) {
        e.preventDefault();
        e.stopPropagation();
        const serviceId = editBtn.getAttribute('data-service-id');
        
        if (serviceId) {
          openEditModal(serviceId);
        }
      }

      // Handle delete button clicks
      let deleteBtn = null;
      
      if (e.target.classList.contains('delete-btn')) {
        deleteBtn = e.target;
      } else if (e.target.closest('.delete-btn')) {
        deleteBtn = e.target.closest('.delete-btn');
      }
      
      if (deleteBtn) {
        e.preventDefault();
        e.stopPropagation();
        const serviceId = deleteBtn.getAttribute('data-service-id');
        
        if (serviceId) {
          openDeleteConfirmation(serviceId);
        }
      }

      // Handle status tracking button clicks
      let statusBtn = null;
      
      if (e.target.classList.contains('status-tracking-btn')) {
        statusBtn = e.target;
      } else if (e.target.closest('.status-tracking-btn')) {
        statusBtn = e.target.closest('.status-tracking-btn');
      }
      
      if (statusBtn) {
        e.preventDefault();
        e.stopPropagation();
        const serviceId = statusBtn.getAttribute('data-service-id');
        
        if (serviceId) {
          openStatusTrackingModal(serviceId);
        }
      }
    });

    // Function to open edit modal and load service data
    function openEditModal(serviceId) {
      // Check if modal elements are available
      const currentEditModal = document.getElementById('edit-modal-overlay');
      if (!currentEditModal) {
        showNotification('Edit functionality not available', 'error');
        return;
      }
      
      // Show loading state
      showNotification('Loading service details...', 'info');
      
      // Fetch service data
      fetch(`/service/${serviceId}/edit`, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
      .then(response => {
        
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        if (data.service) {
          // Populate form fields
          document.getElementById('edit_profile_id').value = data.service.profile_id || '';
          document.getElementById('edit_name').value = data.service.name || '';
          document.getElementById('edit_gender').value = data.service.member_gender || '';
          document.getElementById('edit_mobile').value = data.service.contact_mobile_no || '';
          document.getElementById('edit_contact_alternate').value = data.service.contact_alternate || '';
          
          // Populate edit comment field with previously saved comment
          const editCommentField = document.getElementById('edit_comment');
          if (editCommentField) {
            editCommentField.value = data.service.edit_comment || '';
          }
          
          // Populate status field with current status
          const editStatusField = document.getElementById('edit_status');
          if (editStatusField) {
            if (data.service.status && ['postponed','deleted','RM changed'].includes(data.service.status)) {
              editStatusField.value = data.service.status;
            } else {
              editStatusField.value = '';
            }
          }
          
          // Handle Service Executive and RM Change
          const currentServiceExecutive = data.service.service_executive || '';
          
          // Check if user is admin or staff (different elements)
          const dropdownElement = document.getElementById('edit_service_executive');
          const displayElement = document.getElementById('edit_service_executive_display');
          const readonlyElement = document.getElementById('edit_service_executive_readonly');
          
          if (dropdownElement) {
            // Admin user - show dropdown
            dropdownElement.style.display = 'block';
            displayElement.style.display = 'none';
            dropdownElement.value = currentServiceExecutive;
          } else if (readonlyElement) {
            // Non-admin user - show readonly field
            readonlyElement.value = currentServiceExecutive;
          }
          
          // Reset RM change dropdown (if exists - admin only)
          const rmChangeDropdown = document.getElementById('edit_rm_change');
          if (rmChangeDropdown) {
            rmChangeDropdown.value = '';
          }
          
          // Display RM Change History in green box (Admin only)
          const historyBox = document.getElementById('rm_history_box');
          const historyContent = document.getElementById('rm_history_content');
          
          if (data.service.rm_change_history && historyBox && historyContent) {
            try {
              const history = JSON.parse(data.service.rm_change_history);
              if (history.length > 0) {
                let historyHtml = `<strong>RM Change History (${history.length} changes):</strong><br>`;
                history.forEach((change, index) => {
                  const changeDate = new Date(change.changed_at).toLocaleDateString();
                  historyHtml += `${index + 1}. ${change.from} → ${change.to} by ${change.changed_by} (${changeDate})<br>`;
                });
                
                historyContent.innerHTML = historyHtml;
                historyBox.style.display = 'block';
              } else {
                historyBox.style.display = 'none';
              }
            } catch (e) {
              // Hide box if JSON parse fails
              historyBox.style.display = 'none';
            }
          } else if (historyBox) {
            // No history - hide the box
            historyBox.style.display = 'none';
          }
          
          // Set form action
          editForm.action = `/service/${serviceId}`;
          
          // Show modal
          editModal.classList.add('active');
          document.body.style.overflow = 'hidden';
        } else {
          showNotification('Failed to load service details', 'error');
        }
      })
      .catch(error => {
        showNotification('Failed to load service details', 'error');
      });
    }

    // Close edit modal
    closeEditModalBtn.addEventListener('click', function() {
      editModal.classList.remove('active');
      document.body.style.overflow = 'auto';
      // Clear form when closing
      if (editForm) {
        editForm.reset();
      }
    });

    // Close modal on outside click
    editModal.addEventListener('click', function(e) {
      if (e.target === editModal) {
        editModal.classList.remove('active');
        document.body.style.overflow = 'auto';
        // Clear form when closing
        if (editForm) {
          editForm.reset();
        }
      }
    });

    // Custom ValidationError class
    class ValidationError extends Error {
      constructor(message, errors) {
        super(message);
        this.name = 'ValidationError';
        this.errors = errors;
      }
    }

    // Delete functionality
    let currentServiceIdForDeletion = null;
    const deleteConfirmationModal = document.getElementById('delete-confirmation-modal');
    const deleteCommentModal = document.getElementById('delete-comment-modal');
    const deleteForm = document.getElementById('delete-service-form');
    const cancelDeleteBtn = document.getElementById('cancel-delete-btn');
    const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
    const cancelDeleteCommentBtn = document.getElementById('cancel-delete-comment-btn');

    function openDeleteConfirmation(serviceId) {
      currentServiceIdForDeletion = serviceId;
      deleteConfirmationModal.classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    // Handle confirmation modal buttons
    confirmDeleteBtn.addEventListener('click', function() {
      deleteConfirmationModal.classList.remove('active');
      deleteCommentModal.classList.add('active');
      // Set form action
      deleteForm.action = `/service/${currentServiceIdForDeletion}`;
    });

    cancelDeleteBtn.addEventListener('click', function() {
      deleteConfirmationModal.classList.remove('active');
      document.body.style.overflow = 'auto';
      currentServiceIdForDeletion = null;
    });

    cancelDeleteCommentBtn.addEventListener('click', function() {
      deleteCommentModal.classList.remove('active');
      document.body.style.overflow = 'auto';
      currentServiceIdForDeletion = null;
    });

    // Close delete modals on outside click
    deleteConfirmationModal.addEventListener('click', function(e) {
      if (e.target === deleteConfirmationModal) {
        deleteConfirmationModal.classList.remove('active');
        document.body.style.overflow = 'auto';
        currentServiceIdForDeletion = null;
      }
    });

    deleteCommentModal.addEventListener('click', function(e) {
      if (e.target === deleteCommentModal) {
        deleteCommentModal.classList.remove('active');
        document.body.style.overflow = 'auto';
        currentServiceIdForDeletion = null;
      }
    });

    // Handle delete form submission
    deleteForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Validate comment field
      const commentField = document.getElementById('delete_comment');
      if (!commentField || !commentField.value.trim()) {
        showNotification('Please enter a reason for deleting this service', 'error');
        if (commentField) {
          commentField.focus();
          commentField.style.border = '2px solid #dc3545';
        }
        return;
      }
      
      if (commentField.value.trim().length < 10) {
        showNotification('Comment must be at least 10 characters long', 'error');
        commentField.focus();
        commentField.style.border = '2px solid #dc3545';
        return;
      }
      
      // Reset border color if validation passes
      commentField.style.border = '1px solid #ddd';
      
      // Collect all required fields for update
      const formData = new FormData();
      formData.append('profile_id', document.getElementById('edit_profile_id').value || '');
      formData.append('name', document.getElementById('edit_name').value || '');
      formData.append('member_gender', document.getElementById('edit_gender').value || '');
      formData.append('contact_mobile_no', document.getElementById('edit_mobile').value || '');
      formData.append('contact_alternate', document.getElementById('edit_contact_alternate').value || '');
      formData.append('edit_comment', document.getElementById('edit_comment').value || '');
      // Service executive (admin: dropdown, staff: readonly)
      const execDropdown = document.getElementById('edit_service_executive');
      const execReadonly = document.getElementById('edit_service_executive_readonly');
      if (execDropdown && execDropdown.style.display !== 'none') {
        formData.append('service_executive', execDropdown.value || '');
      } else if (execReadonly) {
        formData.append('service_executive', execReadonly.value || '');
      }
      // RM change (admin only)
      const rmChange = document.getElementById('edit_rm_change');
      if (rmChange) {
        formData.append('rm_change', rmChange.value || '');
      }
      // Status
      formData.append('status', document.getElementById('edit_status').value || '');
      // CSRF and method spoofing
      formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
      formData.append('_method', 'PUT');
      
      fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
      .then(response => {
        if (!response.ok && response.status === 422) {
          return response.json().then(data => {
            throw new ValidationError(data.message, data.errors);
          });
        }
        return response.json();
      })
      .then(data => {
        if (data.success) {
          showNotification('Service deleted successfully!', 'success');
          // Close modal and reset form
          deleteCommentModal.classList.remove('active');
          document.body.style.overflow = 'auto';
          currentServiceIdForDeletion = null;
          // Reload the page to refresh the service list
          setTimeout(() => window.location.reload(), 1000);
        } else {
          showNotification(data.message || 'Failed to delete service', 'error');
        }
      })
      .catch(error => {
        if (error instanceof ValidationError) {
          showNotification(error.message, 'error');
          // Highlight the comment field if it has errors
          const commentField = document.getElementById('delete_comment');
          if (error.errors && error.errors.delete_comment && commentField) {
            commentField.style.border = '2px solid #dc3545';
            commentField.focus();
          }
        } else {
          showNotification('Failed to delete service', 'error');
        }
      });
    });

    // Handle edit form submission
    editForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Validate comment field
      const commentField = document.getElementById('edit_comment');
      if (!commentField || !commentField.value.trim()) {
        showNotification('Please enter a comment explaining the reason for this edit', 'error');
        if (commentField) {
          commentField.focus();
          commentField.style.border = '2px solid #dc3545';
        }
        return;
      }
      
      if (commentField.value.trim().length < 10) {
        showNotification('Comment must be at least 10 characters long', 'error');
        commentField.focus();
        commentField.style.border = '2px solid #dc3545';
        return;
      }
      
      // Reset border color if validation passes
      commentField.style.border = '1px solid #ddd';
      
      const formData = new FormData(this);
      
      fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
      .then(response => {
        if (!response.ok && response.status === 422) {
          return response.json().then(data => {
            throw new ValidationError(data.message, data.errors);
          });
        }
        return response.json();
      })
      .then(data => {
        if (data.success) {
          showNotification('Service updated successfully! Edit has been tracked.', 'success');
          // Close modal and reset form
          editModal.classList.remove('active');
          document.body.style.overflow = 'auto';
          // Reload the page to refresh the service list
          setTimeout(() => window.location.reload(), 1000);
        } else {
          showNotification(data.message || 'Failed to update service', 'error');
        }
      })
      .catch(error => {
        if (error instanceof ValidationError) {
          showNotification(error.message, 'error');
          // Highlight the comment field if it has errors
          const commentField = document.getElementById('edit_comment');
          if (error.errors && error.errors.edit_comment && commentField) {
            commentField.style.border = '2px solid #dc3545';
            commentField.focus();
          }
        } else {
          showNotification('Failed to update service', 'error');
        }
      });
    });

    // Add real-time validation for comment field
    document.addEventListener('input', function(e) {
      if (e.target && (e.target.id === 'edit_comment' || e.target.id === 'delete_comment')) {
        const comment = e.target.value.trim();
        const charCount = comment.length;
        const isDeleteComment = e.target.id === 'delete_comment';
        
        // Remove existing feedback
        const existingFeedback = e.target.parentElement.querySelector('.comment-feedback');
        if (existingFeedback) {
          existingFeedback.remove();
        }
        
        // Create feedback element
        const feedback = document.createElement('div');
        feedback.className = 'comment-feedback';
        feedback.style.cssText = 'font-size: 11px; margin-top: 5px;';
        
        if (charCount === 0) {
          feedback.style.color = '#dc3545';
          feedback.textContent = isDeleteComment ? 'Comment is required for deletion' : 'Comment is required for editing';
          e.target.style.border = '2px solid #dc3545';
        } else if (charCount < 10) {
          feedback.style.color = '#ffc107';
          feedback.textContent = `Comment must be at least 10 characters (${charCount}/10)`;
          e.target.style.border = '2px solid #ffc107';
        } else {
          feedback.style.color = '#28a745';
          feedback.textContent = `Valid comment (${charCount} characters)`;
          e.target.style.border = '2px solid #28a745';
        }
        
        e.target.parentElement.appendChild(feedback);
      }
    });

    // RM Change functionality (Admin only)
    const rmChangeSelect = document.getElementById('edit_rm_change');
    if (rmChangeSelect) {
      rmChangeSelect.addEventListener('change', function() {
        const selectedRM = this.value;
        const serviceExecutiveDropdown = document.getElementById('edit_service_executive');
        const serviceExecutiveDisplay = document.getElementById('edit_service_executive_display');
        
        // Safety check - these elements only exist for admin users
        if (!serviceExecutiveDropdown || !serviceExecutiveDisplay) {
          return;
        }
        
        if (selectedRM && selectedRM !== '') {
          // Get current executive from dropdown
          const currentExecutive = serviceExecutiveDropdown.value;
          
          // Get the executive name for display
          const executiveText = serviceExecutiveDropdown.options[serviceExecutiveDropdown.selectedIndex].text;
          const newExecutiveText = rmChangeSelect.options[rmChangeSelect.selectedIndex].text;
          
          // Switch to read-only mode to show the change preview
          serviceExecutiveDropdown.style.display = 'none';
          serviceExecutiveDisplay.style.display = 'block';
          serviceExecutiveDisplay.value = `${executiveText} → ${newExecutiveText} (Changing...)`;
          
          // Update the hidden dropdown value to the new RM
          serviceExecutiveDropdown.value = selectedRM;
          
          // Add visual indication of change
          serviceExecutiveDisplay.style.backgroundColor = '#fff3cd';
          serviceExecutiveDisplay.style.border = '2px solid #ffc107';
          serviceExecutiveDisplay.style.color = '#856404';
          
          // Show change notification
          showNotification(`RM will be changed from "${executiveText}" to "${newExecutiveText}" when you save`, 'warning');
        } else {
          // RM dropdown is empty - allow normal Service Executive editing
          serviceExecutiveDropdown.style.display = 'block';
          serviceExecutiveDisplay.style.display = 'none';
          
          // Reset display field styling
          serviceExecutiveDisplay.style.backgroundColor = '#f5f5f5';
          serviceExecutiveDisplay.style.border = '1px solid #ddd';
          serviceExecutiveDisplay.style.color = '#333';
          
          // Clear any change notification
          showNotification('RM change cleared - you can now edit Service Executive normally', 'info');
        }
      });
    }

    // Status Tracking Modal Functions
    // Close status modal on close (X) button click
    const closeStatusModalBtn = document.getElementById('close-status-modal-btn');
    if (closeStatusModalBtn) {
      closeStatusModalBtn.addEventListener('click', function() {
        statusTrackingModal.classList.remove('active');
        document.body.style.overflow = 'auto';
      });
    }
  const statusTrackingModal = document.getElementById('status-tracking-modal');

    // Function to open status tracking modal
    function openStatusTrackingModal(serviceId) {
  // No loading notification as requested
      fetch(`/service/${serviceId}/edit`, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Failed to fetch service data');
        }
        return response.json();
      })
      .then(data => {
        if (data.service) {
          // Populate status history table
          const historyTableBody = document.getElementById('status-history-body');
          let historyHTML = '';
          const entries = [];
          // Always show the latest edit as a status history entry
          if (data.service) {
            entries.push({
              date: data.service.updated_at || data.service.tracking_date || data.service.created_at || new Date().toISOString(),
              status: data.service.status || 'Edit',
              comment: data.service.edit_comment || 'No comment available',
              updatedBy: data.service.tracking_updated_by || 'Unknown'
            });
          }
          entries.sort((a, b) => new Date(b.date) - new Date(a.date));
          if (entries.length > 0) {
            entries.forEach((entry, idx) => {
              // Format date as yyyy-mm-dd (no time)
              const d = new Date(entry.date);
              const formattedDate = d.getFullYear() + '-' + String(d.getMonth()+1).padStart(2,'0') + '-' + String(d.getDate()).padStart(2,'0');
              let statusBadge = '';
              if (entry.status === 'postponed') {
                statusBadge = '<span style="background: #ffc107; color: #000; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: bold;">POSTPONED</span>';
              } else if (entry.status === 'deleted') {
                statusBadge = '<span style="background: #dc3545; color: white; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: bold;">DELETED</span>';
              } else if (entry.status === 'RM changed') {
                statusBadge = '<span style="background: #17a2b8; color: white; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: bold;">RM CHANGED</span>';
              } else {
                statusBadge = '<span style="background: #6c757d; color: white; padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: bold;">EDIT</span>';
              }
              // Show only last line or first 60 chars, with 'more' link if needed
              let comment = entry.comment || '';
              let shortComment = comment;
              let showMore = false;
              if (comment.includes('\n')) {
                const lines = comment.split(/\r?\n/);
                shortComment = lines[lines.length-1];
                showMore = lines.length > 1;
              } else if (comment.length > 60) {
                shortComment = comment.slice(-60);
                showMore = true;
              }
              const commentId = `status-comment-${idx}`;
              historyHTML += `
                <tr style="border-bottom: 1px solid #ddd;">
                  <td style="padding: 12px; border: 1px solid #ddd; vertical-align: top;">${formattedDate}</td>
                  <td style="padding: 12px; border: 1px solid #ddd; vertical-align: top;">${statusBadge}</td>
                  <td style="padding: 12px; border: 1px solid #ddd; vertical-align: top; line-height: 1.4;">
                    <span id="${commentId}-short">${shortComment.replace(/</g,'&lt;').replace(/>/g,'&gt;')}</span>
                    ${showMore ? `<a href="#" class="show-more-comment" data-comment-id="${commentId}" style="color:#007bff; text-decoration:underline; margin-left:8px; font-size:12px;">more</a>` : ''}
                    <span id="${commentId}-full" style="display:none;">${comment.replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>')}</span>
                  </td>
                  <td style="padding: 12px; border: 1px solid #ddd; vertical-align: top; font-weight: 500;">${entry.updatedBy}</td>
                </tr>
              `;
            });
          // Add event listeners for 'more' links after table is rendered
          setTimeout(() => {
            document.querySelectorAll('.show-more-comment').forEach(link => {
              if (!link.dataset.bound) {
                link.addEventListener('click', function(e) {
                  e.preventDefault();
                  const id = this.getAttribute('data-comment-id');
                  document.getElementById(id+'-short').style.display = 'none';
                  this.style.display = 'none';
                  document.getElementById(id+'-full').style.display = 'inline';
                });
                link.dataset.bound = '1';
              }
            });
          }, 0);
          } else {
            historyHTML = `
              <tr>
                <td colspan="4" style="padding: 20px; text-align: center; color: #666; font-style: italic; border: 1px solid #ddd;">
                  No status history found for this service
                </td>
              </tr>
            `;
          }
          historyTableBody.innerHTML = historyHTML;
          statusTrackingModal.classList.add('active');
          document.body.style.overflow = 'hidden';
          // Removed notification as requested
        }
      })
      .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to load service data', 'error');
      });
    }


    // Add CSS animation keyframes
    const style = document.createElement('style');
    style.textContent = `
      @keyframes fadeIn {
        from {
          opacity: 0;
          transform: translateY(-10px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
    `;
    document.head.appendChild(style);
  </script>

</body>
</html>