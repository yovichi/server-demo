<?php 
    //API Docs: https://developer.paypal.com/docs/api/invoicing/v2
    
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');

    // Generate API Access Token
    $ch = curl_init();
    $clientId = "AbKQVNDCSq3I0kUYaiq2wsA98yoZcThOhwcXJLpyJzXJU9j2QJ4mDElgRAO0rQamXjp_Dawet0NOMExS";
    $secret = "EDaLuf4MKKVA_86sXkAc8Nr7Lo3Y3jkKb3IjggR8sxjSjmxOLl3oYPugI_JguxU0kN7jZsZOuJ5zN_is";

    curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/oauth2/token");
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_USERPWD, $clientId.":".$secret);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

    $result = curl_exec($ch);
    curl_close($ch);

    if(empty($result))
        die("Error: No response.");
    else
    {
        $json = json_decode($result);
        $access_token = $json->access_token;

        // echo $access_token;

        // Add Paypal Invoices

        $data = '{
          "detail": {
            "invoice_number": "#123",
            "reference": "deal-ref",
            "invoice_date": "2018-11-12",
            "currency_code": "USD",
            "note": "Thank you for your business.",
            "term": "No refunds after 30 days.",
            "memo": "This is a long contract",
            "payment_term": {
              "term_type": "NET_10",
              "due_date": "2018-11-22"
            }
          },
          "primary_recipients": [
            {
              "billing_info": {
                "name": {
                  "given_name": "Stephanie",
                  "surname": "Meyers"
                },
                "address": {
                  "address_line_1": "1234 Main Street",
                  "admin_area_2": "Anytown",
                  "admin_area_1": "CA",
                  "postal_code": "98765",
                  "country_code": "US"
                },
                "email_address": "bill-me@example.com",
                "phones": [
                  {
                    "country_code": "001",
                    "national_number": "4884551234",
                    "phone_type": "HOME"
                  }
                ],
                "additional_info_value": "add-info"
              },
              "shipping_info": {
                "name": {
                  "given_name": "Stephanie",
                  "surname": "Meyers"
                },
                "address": {
                  "address_line_1": "1234 Main Street",
                  "admin_area_2": "Anytown",
                  "admin_area_1": "CA",
                  "postal_code": "98765",
                  "country_code": "US"
                }
              }
            }
          ],
          "items": [
            {
              "name": "Yoga Mat",
              "description": "Elastic mat to practice yoga.",
              "quantity": "1",
              "unit_amount": {
                "currency_code": "USD",
                "value": "50.00"
              },
              "tax": {
                "name": "Sales Tax",
                "percent": "7.25"
              },
              "discount": {
                "percent": "5"
              },
              "unit_of_measure": "QUANTITY"
            },
            {
              "name": "Yoga t-shirt",
              "quantity": "1",
              "unit_amount": {
                "currency_code": "USD",
                "value": "10.00"
              },
              "tax": {
                "name": "Sales Tax",
                "percent": "7.25"
              },
              "discount": {
                "amount": {
                  "currency_code": "USD",
                  "value": "5.00"
                }
              },
              "unit_of_measure": "QUANTITY"
            }
          ],
          "amount": {
            "breakdown": {
              "custom": {
                "label": "Packing Charges",
                "amount": {
                  "currency_code": "USD",
                  "value": "10.00"
                }
              },
              "shipping": {
                "amount": {
                  "currency_code": "USD",
                  "value": "10.00"
                },
                "tax": {
                  "name": "Sales Tax",
                  "percent": "7.25"
                }
              },
              "discount": {
                "invoice_discount": {
                  "percent": "5"
                }
              }
            }
          }
        }';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v2/invoicing/invoices");      
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "Authorization: Bearer ".$access_token
        ));

        $result = curl_exec($ch);
        curl_close($ch);

        echo $result;

    }

    
?>