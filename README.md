
## About Mini Aspire API - Assesment (efriel@ymail.com)

Mini Aspire API (Savings and Loan API Application)
Application Flow and Database references taken from My Previous project for Cooperative Banking in Bandung Indonesia

## Import aspire.sql from the app root directory to the mariadb
## Import postman collections from the app root directory first
### All POST/GET Request using Postman

## 1. Register User
### Users > Register
Register new user to add user, customer, and initial savings account
#### form-data
##### name, email, username, password, address

## 2. Login User
### Users > Login Customer
Get all user detail data and token to be attached to the header bearer token as credential to access all API endpoints
#### form-data
##### username, password,

##### {
    "user": {
        "id": 435,
        "username": "efriel",
        "email": "efriel@ymail.com",
        "email_verified_at": null,
        "role_id": 1,
        "created_at": "2023-05-10T00:01:52.000000Z",
        "updated_at": "2023-05-10T00:01:52.000000Z"
    },
    "authorization": {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL3VzZXIvbG9naW4iLCJpYXQiOjE2ODM2NzcyNTAsImV4cCI6MTY4MzY4MDg1MCwibmJmIjoxNjgzNjc3MjUwLCJqdGkiOiIxeXkxUGp3T0lPbFZha2JFIiwic3ViIjoiNDM1IiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.D9v2YgWjIMKvQ6CxPDpaNuYQhAFha0eky1o8zMJZcfo",
        "type": "bearer"
    }
}

## 3. Check Balances
### Users > Balances
Checking savings balances for the logged in user, user must have savings account for the loan process
#### method
##### GET
#### form-data
##### none

## 4. Loan Simulation
### Loan > Loan Simulation
Get the loan plan using API simulator to get the recommendation according to the requested amount 
#### method
##### POST
#### form-data
##### amount (10000 - 100000) dayterm (day of term loans tenure 7-30 day) 

## 5. Loan Request
### Loan > Loan Request
Get the loan by submiting the loan request like the Loan Simulation 
#### method
##### POST
#### form-data
##### amount (10000 - 100000) dayterm (day of term loans tenure 7-30 day) 

## 6. Login Admin
### Admin > Login Admin
Relogin using admin credential to approve the submitted loan 
#### method
##### POST
#### form-data
##### username, password

## 7. Admin Transactions
### Admin > transactions
To get the transaction_id from transaction list
#### method
##### POST

## 8. Login Approval
### Admin > Approve
Relogin using admin credential to approve the submitted loan 
#### method
##### POST
#### form-data
##### transaction_id

## 9. Check Customer Loan Balance
### Loan > Balances
Relogin using Customer credential to check loan balance and to obtain amount of current payment due after get approved by admin
#### method
##### POST
#### form-data
##### username, password

```json
{
    "success": true,
    "data": {
        "user": {
            "id": 435,
            "username": "efriel",
            "email": "efriel@ymail.com",
            "email_verified_at": null,
            "role_id": 1,
            "created_at": "2023-05-10T00:01:52.000000Z",
            "updated_at": "2023-05-10T00:01:52.000000Z"
        },
        "loan": {
            "account_number": 4625,
            "account_type_id": 1,
            "customer_id": 435,
            "day_term": 7,
            "installment": 3,
            "code": "I",
            "created_at": "2023-05-10 00:02:45",
            "updated_at": "2023-05-10 00:10:00",
            "id": 1,
            "account_name": "Loan A",
            "limit": 20000,
            "tenor": 3,
            "gl_code": 2000,
            "name": "Loan Paid"
        },
        "balances": 0,
        "installment": {
            "installment_no": 4,
            "installment_pay": "0.00",
            "installment_duedate": "31st May 2023"
        }
    },
    "message": "Success"
}

```
## 10. Repayment Loan
### Loan > Repayment
Repayment the loan
#### method
##### POST
#### form-data
##### amount,



## License

Aspire Assessment License
