<!DOCTYPE html>
<html>
  <head>
    <title>Parcel Sandbox</title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" href="./src/styles.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
      href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap"
      rel="stylesheet"
    />
  </head>

  <body>
    <div class="voucher-card__container">
      <div class="voucher-card__thumbnail"></div>
      <div class="voucher-card__content">
        <div class="voucher-card__title">{{$detail->name}}<div>
        <div class="voucher-card__time">
          <div class="voucher-card__time-title">Available Time:</div>
          <div class="voucher-card__time-subtitle">From {{$from}} - {{$to}}</div>
        </div>
      </div>
    </div>
    <div style="margin-top: 24px;" />
    <div class="description__container">
      <div class="description__heading-text">Description</div>
      <div style="margin-top: 24px;" />
      <ul>
        <li>
          <b>Objective:</b>
          <span class="description__text">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
          </span>
        </li>

        <li>
          <b>Details:</b>
          <span class="description__text">
            {{$detail->description}}
          </span>
        </li>
      </ul>
    </div>
  </body>
  <style type="text/css">
    html {
      font-family: "Open Sans", sans-serif;
    }

    body {
      margin: 0;
      background: #faf9f9;
    }

    .voucher-card__container {
      width: 100%;
      border-radius: 16px 16px 0 0;
      overflow: hidden;
      display: flex;
      flex-direction: column;
    }

    .voucher-card__content {
      background-color: #e9f4ec;
      padding: 8px;
    }

    .voucher-card__title {
      font-style: normal;
      font-weight: 700;
      font-size: 24px;
      line-height: 34px;
      color: #32302d;
    }

    .voucher-card__time {
      margin-bottom: 8px;
    }

    .voucher-card__time-title {
      font-style: normal;
      font-weight: 700;
      font-size: 14px;
      color: #625f5c;
      line-height: 20px;
    }

    .voucher-card__time-subtitle {
      font-size: 14px;
      line-height: 20px;
      color: #469b59;
    }

    .heading-text {
      font-style: normal;
      font-weight: 700;
      font-size: 16px;
      line-height: 24px;
      color: #32302d;
    }

    .description-container {
      line-height: 24px;
    }

    .description__text {
      color: #625f5c;
    }

    .description__heading-text {
      font-style: normal;
      font-weight: 700;
      font-size: 16px;
      line-height: 24px;
      color: #32302d;
    }

    .voucher-card__thumbnail {
      height: 220px;
      width: 100%;
      background: url("{{{ $detail->url_image }}}") top / cover;
    }
  </style>
</html>
