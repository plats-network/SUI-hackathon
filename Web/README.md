# Requirements

- PHP >= 8.0
- PostgreSQL: >= 12

## Postgress
Cài postgress thủ công hoặc docker

```shell
Với docker

docker run --name postgres \
  -e POSTGRES_USER=root \
  -e POSTGRES_PASSWORD=plats#2023 \
  -e TZ=GMT+7 \
  -p 5432:5432 \
  -v /srv/datas/postgres:/var/lib/postgresql/data \
  -d postgres
```

js: https://alpinejs.dev/

# Domain

https://laravel.com/docs/10.x/valet
Tạo Subdomain tham khảo link trên

Local
```
CWS: http://cws.plats.test
EVENT: http://event.plats.test
API: http://api.plats.test

npm run build && npm run dev
```

DEV:
```
CWS: https://dev-cws.plats.network
EVENT: https://dev-event.plats.network
API: https://dev-api.plats.network
```

PROD:
```
CWS: https://cws.plats.network
EVENT: https://event.plats.network
API: https://api.plats.network
```

# Hướng dẫn deploy

1. Copy 2 file ssh vào thư mục .ssh trên máy của mình

```
cp deploy/plats ~/.ssh/
cp deploy/plats.pub ~/.ssh/
```

Phân quyền cho 2 file ssh trên

```
sudo chmod 400 ~/.ssh/plats
sudo chmod 400 ~/.ssh/plats.pub
```

2. Copy file deployer.phar vào thư mục bin trong vendor

```
cp deploy/deployer.phar vendor/bin/
```

Phân quyền cho file trên

```
sudo chmod -R 777 vendor/bin/deployer.phar
```

3. Chạy lênh deploy lên server dev

```
vendor/bin/deployer.phar deploy dev
```
- Chờ kết quả done thì deploy thành công

## Note:
- Nếu ở bước 3 báo lỗi thì vui lòng chạy các lệnh sau rồi chạy lại bước 3

```
eval "$(ssh-agent -s)"
ssh-add ~/.ssh/plats
```
