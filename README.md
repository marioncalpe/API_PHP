# API PHP TP1 RIA
## Routes

- GET
  - `/` : Get the list of all products
  - `/<pdt_id>` : Get the information about the product corresponding of the id `pdt_id`
  - `/<pdt_name>` : Get the information about the product(s) corresponding of the name `pdt_name`
  - `/histo/<pdt_id>` : Get the modification history of the product corresponding to `pdt_id`
- POST
  - `/add` : Add a product
- PATCH
  - `/<pdt_id>` : Update the product of id `pdt_id`
- DELETE
  - `/<pdt_id>` : Delete the product of id `pdt_id`

## Responses
The response is under JSON format of the forms :
```json
{
  status: 200,
  status_message: "Success",
  data: [
    {
      id: 1,
      name: "pen",
      description: "",
      price: 15,
      dateIn: "2019-05-23 16:06",
      dateUp: "2019-05-23 16:06"
    },
    ...
  ]
}
```
