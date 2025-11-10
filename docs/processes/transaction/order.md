# Order Management

Order is a way for system to allow a customer start buying process and order team to fulfill it.

- The order starts when customer adds a  product to cart.
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

- Order can be placed using the shopping cart checkout process
- Upon placing the order , the status changes to `ORDER_IN_PROCESS` from `OrderStatusTypes` class
- Additional row is placed in the status table


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
This data should be immutable once the payment for the order is processed. We are assuming for now that order cannot be modified by anyone once the order is placed.

Scenarios 
- Product data can be changed post order ( spec changed / product discontinued)
- Customer may change address details or name, salutation etc. 
- Price details may change

### How to tackle the changes
- The customer/product/prices data is stored in Json in Order Tables ( new approach needed ?)
- There is no hard link of the JSON data with the original data of product/customer/prices so any changes to master doesn't affect the order data.
- The system should use the order JSON data to ship the orders? 
- If the customer changes the pin code or any other attribute, the product/pricing data may go haywire, so breaking the hard link is necessary. 
- The approach of copying the data into order fields which mimic master data is not possible because it tightly couples order model with master data model, which is not desirable . A better way to store as much data from master into the order json fields as the data can change anytime.

### Future challenges : 
- How to display the data for backend team and also the customer .
- No strategy as of now to keep the images related to the product. 