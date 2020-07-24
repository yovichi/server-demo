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

        // Update Note for Paypal Invoice
        $data = '{
            "id": "INV2-HU6A-FZUG-ARCR-49CV",
            "status": "DRAFT",
            "detail": {
                "currency_code": "USD",
                "note": "Hello World",
                "category_code": "SHIPPABLE",
                "invoice_number": "0001",
                "invoice_date": "2020-07-24",
                "payment_term": {
                    "term_type": "DUE_ON_RECEIPT",
                    "due_date": "2020-07-24"
                },
                "viewed_by_recipient": false,
                "metadata": {
                    "create_time": "2020-07-24T13:32:16Z",
                    "last_update_time": "2020-07-24T13:32:16Z",
                    "created_by_flow": "REGULAR_SINGLE",
                    "recipient_view_url": "https://www.sandbox.paypal.com/invoice/p/#HU6AFZUGARCR49CV",
                    "invoicer_view_url": "https://www.sandbox.paypal.com/invoice/details/INV2-HU6A-FZUG-ARCR-49CV"
                },
                "archived": false
            },
            "invoicer": {
                "business_name": "John Doe\'s Test Store",
                "address": {
                    "address_line_1": "1 Main St",
                    "admin_area_2": "San Jose",
                    "admin_area_1": "CA",
                    "postal_code": "95131"
                },
                "email_address": "sb-digsb2713596@business.example.com",
                "phones": [
                    {
                        "country_code": "1",
                        "national_number": "4089033275",
                        "phone_type": "MOBILE"
                    }
                ]
            },
            "primary_recipients": [
                {
                    "billing_info": {
                        "email_address": "perez.yovichi@gmail.com"
                    }
                }
            ],
            "items": [
                {
                    "id": "ITEM-2G57220638588053V",
                    "name": "Item 1",
                    "quantity": "1",
                    "unit_amount": {
                        "currency_code": "USD",
                        "value": "500.00"
                    }
                }
            ],
            "configuration": {
                "tax_calculated_after_discount": false,
                "tax_inclusive": false,
                "allow_tip": false,
                "template_id": "TEMP-08F69530BF265233H"
            },
            "amount": {
                "breakdown": {
                    "item_total": {
                        "currency_code": "USD",
                        "value": "500.00"
                    },
                    "discount": {
                        "invoice_discount": {
                            "amount": {
                                "currency_code": "USD",
                                "value": "0.00"
                            }
                        },
                        "item_discount": {
                            "currency_code": "USD",
                            "value": "0.00"
                        }
                    },
                    "tax_total": {
                        "currency_code": "USD",
                        "value": "0.00"
                    },
                    "shipping": {
                        "amount": {
                            "currency_code": "USD",
                            "value": "0.00"
                        }
                    }
                },
                "currency_code": "USD",
                "value": "500.00"
            },
            "due_amount": {
                "currency_code": "USD",
                "value": "500.00"
            }
        }';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v2/invoicing/invoices/INV2-HU6A-FZUG-ARCR-49CV");      
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
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