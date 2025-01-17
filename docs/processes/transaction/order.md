# Order Management


## Order Status  

**ORDER_CREATED**  

The order is still a cart order and cannot be seen in order details screen

**ORDER_IN_PROCESS**

Once the order has been placed by customer, this will be the status

....

## Order Journal

Order journal is created everytime after payment has been successfully created. This is essentially created after every stage order is chan ged (TBD) once the order status is set to PAYMENT_SUCCESSFUL

_Why is it needed?_
 - For auditing
 - A customer may remove their addresses, their account after placing the order
 - A product may be deactivated/removed
 - A snapshot of order status at current time and through-out history