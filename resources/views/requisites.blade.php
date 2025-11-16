@extends('layouts.main')

@section('title', 'Реквизиты')

@section('some_styles')
  <style>
    .main__container {
      padding: 25px;
      background-color: #fff;
      border-radius: 20px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
      overflow-x: auto;
    }

    .requisites-table {
      width: 100%;
      border-collapse: collapse;
      font-family: Arial, sans-serif;
    }

    .requisites-table td {
      padding: 12px 15px;
      border-bottom: 1px solid #eaeaea;
      transition: background-color 0.2s ease-in-out;
    }

    .requisites-table td:first-child {
      font-weight: 600;
      color: #555;
      width: 45%;
      background-color: #fafafa;
      border-right: 1px solid #eaeaea;
    }

    .requisites-table tr:hover td {
      background-color: #f9f9ff;
    }

    .requisites-table a {
      color: #007bff;
      text-decoration: none;
    }

    .requisites-table a:hover {
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      .requisites-table td {
        display: block;
        width: 100%;
      }

      .requisites-table td:first-child {
        border-right: none;
        background-color: transparent;
        padding-bottom: 5px;
      }

      .requisites-table tr {
        margin-bottom: 15px;
        display: block;
        border-bottom: 2px solid #eaeaea;
      }
    }
  </style>
@endsection

@section('page_title')
  <h2 class="page-title">Реквизиты</h2>
@endsection

@section('content')
<div class="main__container">
  <table class="requisites-table">
    <tr>
      <td>Полное наименование</td>
      <td>Общество с ограниченной ответственностью "ДИДЖИТАЛ САЙНС СОФТ"</td>
    </tr>
    <tr>
      <td>Сокращенное наименование</td>
      <td>ООО "ДИДЖИТАЛ САЙНС СОФТ"</td>
    </tr>
    <tr>
      <td>Юридический адрес</td>
      <td>г. Тюмень, ул. Республики, д. 142, помещ. 35, кабинет 320, РМ 11</td>
    </tr>
    <tr>
      <td>Почтовый адрес</td>
      <td>г. Тюмень, ул. Республики, д. 142, помещ. 35, кабинет 320, РМ 11</td>
    </tr>
    <tr>
      <td>ИНН / КПП</td>
      <td>7203583745 / 720301001</td>
    </tr>
    <tr>
      <td>ОГРН</td>
      <td>1247200019703</td>
    </tr>
    <tr>
      <td>Расчетный счет</td>
      <td>40702810410001707163</td>
    </tr>
    <tr>
      <td>Корреспондентский счет</td>
      <td>30101810145250000974</td>
    </tr>
    <tr>
      <td>БИК банка</td>
      <td>044525974</td>
    </tr>
    <tr>
      <td>Банк</td>
      <td>АО "ТБанк"</td>
    </tr>
    <tr>
      <td>ОКВЭД</td>
      <td>62.01</td>
    </tr>
    <tr>
      <td>Директор</td>
      <td>Гындыбин Михаил Викторович, действует на основании Устава</td>
    </tr>
    <tr>
      <td>E-mail</td>
      <td><a href="mailto:mgyndybin@gmail.com">mgyndybin@gmail.com</a></td>
    </tr>
  </table>
</div>
@endsection
