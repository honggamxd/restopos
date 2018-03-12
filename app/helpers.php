<?php
  function settlements($type)
  {
    switch ($type) {
      case 'cash':
        return 'Cash';
        break;
      case 'credit':
        return 'Credit Card';
        break;
      case 'debit':
        return 'Debit Card';
        break;
      case 'cheque':
        return 'Cheque';
        break;
      case 'guest_ledger':
        return 'Guest Ledger';
        break;
      case 'send_bill':
        return 'Send Bill';
        break;
      case 'free_of_charge':
        return 'FOC';
        break;
      case 'cancelled':
        return 'Cancelled / Void';
        break;
      case 'bad_order':
        return 'BOD Charge';
        break;
      case 'staff_charge':
        return 'Staff Charge';
        break;
      case 'bod':
        return 'BOD';
        break;
      case 'manager_meals':
        return 'Managers Meal';
        break;
      case 'sales_office':
        return 'Sales Office';
        break;
      case 'representation':
        return 'Representation';
        break;
      case 'package_inclusion':
        return 'Package Inclusion';
        break;
      case 'kitchen_use':
        return 'Kitchen Use';
        break;
      default:
        return '';
        break;
    }
  }