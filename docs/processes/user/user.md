# Users in Silecust
Every living entity(for now customer and employee) in Silecust is a user  

## User as a customer
A customer is connected to User by id of user. While a user has generic data like login and password, customer can have more customer specific data like names

## User as an employee
An employee is connected to User by id of user. While a user has generic data like login and password, employee can have more employee specific data like names

## User as a super admin
At the installation you have a super admin user command which helps to create users later on. The Admin user can see all menu items and thus needs to get a strong password while creation

# Sign In As a customer
User can sign in as a customer from the url `/signin`. Only customers can use this method and employees cannot be created by it

# Login as Customer/Employee
User can use `/login` to log in as customer or employee ( depending on their roles in the system)
Customers and employees will see different menu items on dashboard

# Forgot Password
User can reset password by going to `/reset-password` while logged out.

# Reset Password from Dashboard
TBD

# Update User data
TBD

# Emails sent during various processes
 Documentation TBD