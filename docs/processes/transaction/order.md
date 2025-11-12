# Order Management

Order is a way for system to allow a customer start buying process and order team to fulfill it.

- The order starts when customer adds a product to cart.
- The customer then checks out the order and makes payment
- The order is processed by the backend team and is sent for shipping
- The order can be marked as closed post completion

## Order Creation

- Orders are created when user adds a product to the cart
- The status of order is `OrderStatusType -> ORDER_CREATED`
- This order is right now not visible to the order listing
- The creation of order creates a status in order status
- A customer may manipulate the cart, even empty it, the order remains existing
    - The order however mimics the cart items
    - The order for now till checkout is complete contains only basic prices
- If customer logs out and re-logs in , cart is populated with this open order
- Currently time stamp/status are also a part of header, which was for convenience, but it should be taken from _order
  status table_

## Placing the order

### Status movement

Following are the status proposed in the system.

- ORDER_CREATED: When user adds a product in a new cart. Cart order once created remains in the system forever
- ORDER_PAYMENT_COMPLETED: When user finishes payment
- ORDER_PAYMENT_FAILURE: When user fails to make a payment
- ORDER_IN_PROCESS: Post payment , order processing
- ORDER_SHIPPED: Order has been shipped
- ORDER_COMPLETED: Order has been marked complete upon delivery

All these status are available in class `OrderStatusTypes`

- Each status has note field ( the details are not yet implemented )
-

## Impact of order status changes

All status changes will create a record in status table.

- ORDER_CREATED : It is an implicit order and it will not be visible to customer
- ORDER_PAYMENT_COMPLETED: It will add a record to status and also payment information in `OrderPayment` table

# Journal:

- Some changes are recorded in journal based on predefined data whenever data is changed in the order

Future TODO:

- Rules for moving from status A to B and back
- Status like ORDER_CANCELLED
- User level status for each system type status.

## Order Auditing

- For legal reasons order data is to be preserved under changes

At anytime order data needs to pulled up . It should have

- Customer data
    - Name/Salutation
    - Addresses ( both shipping and billing )
- Product Data
    - Product name/description
    - Product Attributes
- Prices
    - Price of product
    - discount
    - taxes
- Order data
    - Shipping charges
    - Status changes
    - Status changes by whom
      This data should be immutable once the payment for the order is processed. We are assuming for now that order
      cannot be modified by anyone once the order is placed.

Scenarios

- Product data can be changed post order ( spec changed / product discontinued)
- Customer may change address details or name, salutation etc.
- Price details may change

### How to tackle the changes

- The customer/product/prices data is stored in Json in Order Tables ( new approach needed ?)
- There is no hard link of the JSON data with the original data of product/customer/prices so any changes to master
  doesn't affect the order data.
- The system should use the order JSON data to ship the orders?
- If the customer changes the pin code or any other attribute, the product/pricing data may go haywire, so breaking the
  hard link is necessary.
- The approach of copying the data into order fields which mimic master data is not possible because it tightly couples
  order model with master data model, which is not desirable . A better way to store as much data from master into the
  order json fields as the data can change anytime.

### Future challenges :

- How to display the data for backend team and also the customer .
- No strategy as of now to keep the images related to the product.

## Important information

### Order create date:

~~ For easy search, order create date and time is added to the Order Header Table .

- It is same as Date Time when the order status is ORDER_IN_PROCESS in the OrderStatus Table // todo: Create test case
  to check it
- This date time can be shown to the customer
- The ORDER_CREATED date will be the time , order was created implicitly using the cart
- This date is immutable. Once set , it cannot be changed by the application ~~

- Order create date is ambiguous and is removed in this commit.
- The order date shown to customer is to be taken from OrderStatus Table
- Order status is also removed from OrderHeader. The last status in OrderStatus Table is the status of the order

#### How does this date store time zone

- The timezone of the server can be set in php.ini using

`; Defines the default timezone used by the date functions
  ; https://php.net/date.timezone
  ;date.timezone =
`

The date in database is always stored in UTC. But due to daylight saving issues, the date can cause inconsistencies in
the way date time is retrieved. To avoid this, a package is used where date is converted to UTC everytime it is saved.
Time zone ( derived from php.ini settings) is stored separately .

Note: Microtime is not supported

See articles :

- https://www.php.net/manual/en/function.date-default-timezone-get.php
- https://www.doctrine-project.org/projects/doctrine-dbal/en/4.3/reference/types.html
- https://github.com/simPod/doctrine-utcdatetime
- https://www.doctrine-project.org/projects/doctrine-orm/en/3.5/cookbook/working-with-datetime.html
