<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invoice Report</title>
    <style>
      body{
        width: 100%;
      }
      table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 98%;
        margin: 0 auto;
      
      }
   
      td,
      th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
        line-height: 1.3rem;
      }

      tr:nth-child(even) {
        background-color: #dddddd;
      }
      a {
        text-decoration: none;
      }
    </style>

  </head>
  <body>
    <div>
    <table>
      <tr>
        <td style="border: none"></td>
        <th style="border: none; text-align: center">
          <img
            src="{{ asset('admin-assets/assets/img/aranya-logo-dark.png') }}"
            alt="Aranya Logo"
            width="50px"
          />
        </th>

        <td style="border: none; text-align: right"></td>
      </tr>
    </table>
    <hr style="width: 98%;
        margin: 0 auto;" />
    <h4 style="border: none; text-align: center">Invoice Report</h4>

    <table style="margin-top: 2rem">
        <tr style="background-color: #5b93b4; border: none">
            <th>OrderID</th>
            <th>Order Date</th>
            <th>Customer</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Address</th>
            <th>Delivery</th>
            <th>Item Qty</th>
            <th>Amount</th>
            <th>Profit</th>
            <th class="text-center">Payment Method</th>
            <th class="text-center">Payment Gateway</th>
        </tr>
        @for($i = 1; $i < 6; $i++)
        <tr>
            <td>234</td>
            <td>2023-05-21</td>
            <td>Juston Yundt</td>
            <td>01824220930</td>
            <td>jacey06@example.net</td>
            <td>Sher-shah-shuri road, Mohammodpur, Dhaka</td>
            <td>Delivered</td>
            <td>3</td>
            <td>19560</td>
            <td>4680</td>
            <td>Online</td>
            <td>sslCommerz</td>
        </tr>
        @endfor
    </table>
   
    </div>
  </body>
</html>