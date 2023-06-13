
# Ideasoft Assesment

Proje Kurulumu İçin Lütfen Bilgisayarınızda Docker'ın Kurulu Olduğundan emin olunuz
Proje Dosyalarını src/Ideasoft klasörü altında bulabilirsiniz.

#### Note: "Vhost için domaininizi lütfen ideasotf.local olarak ayarlayın"

## Kurulum Aşamaları
### Lütfen Aşağıdaki Komutları Proje Yolu İçerisinde Çalıştırın

```bash
 docker compose build
 docker compose up
 docker-compose exec php php artisan migrate
 docker-compose exec php php artisan db:seed
```




## API Kullanımı

Device register isteğinden gelen token'ı diğer isteklerde Header'a Client-Token olarak set edilmsei gerekmektedir
#### Customer List Request

```Http
  GET /api/customer

  {
      uid:              string|required
      appId:            string|required
      language:         string|required
      operating-system: string|required  
  }
```

#### Customer Register Request

```http
  POST /api/customer/register
   {
           name: string|required
          email: string|required
       password: string|required
  }
```
#### Customer Login Request

```http
  POST /api/customer/login
   {
            name: string
           email: string
  }
```

#### Product List Request

```http
  GET /api/product
   {
   }
```

#### Product Store Request

```http
  POST /api/product
   {
           name: string|required
          price: float|required
          stock: int|required
     categories: array|required //add category id to this for adding one ore more category to this product 
  }
```

#### Product Update Request

```http
    PUT /api/product/{id}
   {
            name: string
           price: float
           stock:  int
     categories: array //add category id to this for adding one ore more category to this product 
  }
```

#### Product Delete Request

```http
   Delete /api/product/{id}
   {
   }
```

#### Category List Request

```http
  GET /api/category
   {
   }
```

#### Category Store Request

```http
  POST /api/category
   {
           name: string|required
   }
```

#### Category Update Request

```http
    PUT /api/caetgory/{id}
   {
            name: string
   }
```

#### Category Delete Request

```http
   Delete /api/category/{id}
   {
   }
```
#### Order List Request

```http
  GET /api/order
   {
   }
```

#### Order Discounts List Request

```http
  GET /api/order-discounts
   {
   }
```

## Add Bearer token from login request to header to below requests

#### Customer Product Add to cart Request

```http
  POST /api/cart/add
   {
                products: array|required,
           products.*.id: int|required,
     products.*.quantity: int|required,
   }
```

#### Customer Product Delete from cart or Lower Quantity of Product Request

```http
  POST /api/cart/delete
   {
                all: boolean
                if 'all false or undefined'
                       products: array|required
                  products.*.id: int|required
                  products.*.all boolean
                  if (products.*all false or undefined)
                      products.*.quantity : int|required
   }
```
#### Customer Order Complete Transaction

```http
  POST /api/order
   {
   }
```