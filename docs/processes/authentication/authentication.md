# Authentication
Authentication include Logging in and signing up. 

# Sign Up ( For Customer)
Sign Up is reserved for customer who wish to purchase from the site. The url for sign up is `/signup` 
The role customer get is `ROLE_CUSTOMER`

# Employee Creation
Super user creates employees and they are assigned the role `ROLE_EMPLOYEE`

Upon Signup or Employee creation , a mail is sent to the email id that is used to create the login

# Log in 
Both employee and customer login from the same Url `/login`

# Reset password
Use `/reset-password` to reset the link