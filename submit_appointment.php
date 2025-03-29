<?php

header("Content-Type: application/json; charset=UTF-8");

echo(json_encode(submitAppointment()));

function submitAppointment() {
  $clean = array();

  if (empty($_POST["phone"]) && empty($_POST["email"])) {
    return array(
      "status" => false,
      "message" => "We need either an email or phone number so we can get in touch with you."
    );
  } else {
    if (!empty($_POST["email"])) {
      if (!strrpos($_POST["email"], "@")) {
        return array(
          "status" => false,
          "message" => "Your email address appears to have been entered incorrectly. Please check that you entered it correctly, and try again."
        );
      }
    }

    if (!empty($_POST["phone"])) {
      if (!is_phone($_POST["phone"])) {
        return array(
          "status" => false,
          "message" => "Your phone number appears to have been entered incorrectly. Please make sure your phone number has been entered correctly."
        );
      }
    }

    if (!empty($_POST["alternatePhone"])) {
      if (!is_phone($_POST["alternatePhone"])) {
        return array(
          "status" => false,
          "message" => "Your alternate phone number appears to have been entered incorrectly. Please make sure your alternate phone number has been entered correctly."
        );
      }
    }

    // Filter values into clean
    if (!empty($_POST["name"])) {
      $clean["name"] = str_replace("\r\n", "", $_POST["name"]);
    }

    if (!empty($_POST["address"])) {
      $clean["address"] = str_replace("\r\n", "", $_POST["address"]);
    }

    if (!empty($_POST["phone"])) {
      $clean["phone"] = str_replace("\r\n", "", $_POST["phone"]);
    }

    if (!empty($_POST["alternatePhone"])) {
      $clean["alternatePhone"] = str_replace("\r\n", "", $_POST["alternatePhone"]);
    }

    if (!empty($_POST["email"])) {
      $clean["email"] = str_replace("\r\n", "", $_POST["email"]);
    }

    if (!empty($_POST["problem"])) {
      $clean["problem"] = str_replace("\r\n", "", $_POST["problem"]);
    }

    if (!empty($_POST["preferredTime"])) {
      $clean["preferredTime"] = str_replace("\r\n", "", $_POST["preferredTime"]);
    }

    // Build message
    $message = "You just got an appointment request through the web site! Here's the info the visitor entered." . PHP_EOL . PHP_EOL;

    if (isset($clean["name"])) {
      $message .= "Name: " . $clean["name"] . PHP_EOL;
    }

    if (isset($clean["address"])) {
      $message .= "Address: " . $clean["address"] . PHP_EOL;
    }

    if (isset($clean["phone"])) {
      $message .= "Phone Number: " . $clean["phone"] . PHP_EOL;
    }

    if (isset($clean["alternatePhone"])) {
      $message .= "Alternate Phone Number: " . $clean["alternatePhone"] . PHP_EOL;
    }

    if (isset($clean["email"])) {
      $message .= "Email: " . $clean["email"] . PHP_EOL;
    }

    if (isset($clean["preferredTime"])) {
      $message .= "Preferred Appointment Time: " . $clean["preferredTime"] . PHP_EOL;
    }

    if (isset($clean["problem"])) {
      $message .= "Problem: " . PHP_EOL . $clean["problem"] . PHP_EOL;
    }

    //mail("bryanohern@hotmail.com", "Coyle Appliance Repair Appointment Request", $message, "From: appointments@coyleappliancerepair.com");
    mail("admin@coyleappliancerepair.com", "Coyle Appliance Repair Appointment Request", $message, "From: appointments@coyleappliancerepair.com");
    mail("jeremy.l.wagner@gmail.com", "Coyle Appliance Repair Appointment Request", $message, "From: appointments@coyleappliancerepair.com");

    return array(
      "status" => true,
      "message" => "Your request for an appointment has been sent. A Coyle representative will contact you as soon as possible. Thanks!"
    );
  }
}

function is_phone($phone) {
  return preg_match("/[0-9]{3}\-[0-9]{3}\-[0-9]{4}/i", $phone) || preg_match("/[0-9]{3}\.[0-9]{3}\.[0-9]{4}/i", $phone) || preg_match("/[0-9]{10}/i", $phone);
}

?>
