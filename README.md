# mini-aspire

## description
This is a mini version of Loan repayment system built with Laravel framework. 
The APIs will allows to handle user loans. 
The following features/functions will be includes.

**user's functions**
 - user can register
 - user login via API & token will return (that token have to used for all the functions below)
 - user can request a loan (if there is no outstanding repayment)
 - user can view his requested loans
 - user can view the loan that had offered to him
 - user can accept the offered loan
 - user can make payment
 
 **admin's functions**
  - admin can register
  - admin login via API & token will return (that token have to used for all the functions below)
  - admin can offer a new loan
  - admin can view loan details
  - admin can view overdued loans



## installation

```
(1) create database
(2) php artisan migrate
(3) php artisan passport:client --personal
 ```
## ER Diagram
![aspire mini](https://user-images.githubusercontent.com/18179544/48685706-9dfcbf00-ebf2-11e8-9119-e434a162a8a4.png)

      
