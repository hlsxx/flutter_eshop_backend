<?php

  global $db;

  $data = $db->request_data();

  $db->insert_array([
    'table' => "orders",
    'table_data' => [
      "serial_number" => rand(1000, 9999), // TODO: Toto sa dynamicky podla user_id
      "type" => 1,
      "id_customer_uid" => 1,
      "id_cart" => (int)$data->id_cart
    ]
  ]);

  $db->update(
    tableName: "carts",
    rowId: (int)$data->id_cart,
    data: [
      "is_order" => 1
    ]
  );

  $vsetkyProdukty = $db->dbSelect(
    tableName: "carts_products",
    conditions: [
      "select" => "*",
      "join" => [
        "products" => [
          "id_product",
          "id"
        ]
      ],
      "where" => [
        "id_cart" => (int)$data->id_cart
      ]
    ]
  );

  foreach ($vsetkyProdukty as $produkt) {
    $db->update(
      tableName: "products",
      rowId: $produkt["id_product"],
      data: [
        "count" => ($produkt["count"] - 1)
      ]
    );
  }

   /** 
   * GET inserted item ID 
   * tableName
   * @return int
  */
  echo json_encode($db->getLastItemId("orders"));

?>