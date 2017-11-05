<!DOCTYPE html>
<html>

  @include('include/head')


  <body>
    @include('include/header')

    @include('include/sidebar')

    <div class="main-contents">
      <div class="main-contents__body">
        <ul class="breadcrumbs">
          <li class="breadcrumbs__list"><a href="#">Home</a></li>
          <li class="breadcrumbs__list">Archive template</li>
        </ul>
        <h1 class="page-header"><i class="fa fa-file-text"></i><span>Archive template</span></h1>
        <form method="" action="">
          <div class="panel">
            <div class="panel__head">
              <h2 class="panel__title"><i class="fa fa-search"></i><span>&nbsp;Search Item</span></h2></div>
            <div class="panel__body">
              <dl class="data-list col2"><dt class="data-list__title">Name</dt>
                <dd class="data-list__body"><input type="text" class="input--full"></dd><dt class="data-list__title">E-Mail</dt>
                <dd class="data-list__body"><input type="text" class="input--full"></dd>
              </dl>
            </div>
            <div class="panel__foot">
              <ul class="list-btn align--center">
                <li><button type="reset" class="btn btn--default with-icon"><i class="fa fa-times"></i><span>Clear</span></button></li>
                <li><button type="submit" class="btn btn--primary with-icon"><i class="fa fa-search"></i><span>Search</span></button></li>
              </ul>
            </div>
          </div>
        </form>
        <p class="weight--bold">Result of 5 items</p>
        <table class="table table--striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>E-Mail</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td><a href="#">komeda</a></td>
              <td>komeda@mail.com</td>
              <td class="align--right">
                <ul class="list-btn">
                  <li><a href="#" class="btn btn--default btn--small with-icon"><i class="fa fa-eye"></i><span>Detail</span></a></li>
                  <li><a href="#" class="btn btn--danger btn--small with-icon"><i class="fa fa-trash-o"></i><span>Delete</span></a></li>
                </ul>
              </td>
            </tr>
            <tr>
              <td>2</td>
              <td><a href="#">tanaka</a></td>
              <td>tanaka@mail.com</td>
              <td class="align--right">
                <ul class="list-btn">
                  <li><a href="#" class="btn btn--default btn--small with-icon"><i class="fa fa-eye"></i><span>Detail</span></a></li>
                  <li><a href="#" class="btn btn--danger btn--small with-icon"><i class="fa fa-trash-o"></i><span>Delete</span></a></li>
                </ul>
              </td>
            </tr>
            <tr>
              <td>3</td>
              <td><a href="#">sato</a></td>
              <td>sato@mail.com</td>
              <td class="align--right">
                <ul class="list-btn">
                  <li><a href="#" class="btn btn--default btn--small with-icon"><i class="fa fa-eye"></i><span>Detail</span></a></li>
                  <li><a href="#" class="btn btn--danger btn--small with-icon"><i class="fa fa-trash-o"></i><span>Delete</span></a></li>
                </ul>
              </td>
            </tr>
            <tr>
              <td>4</td>
              <td><a href="#">takahashi</a></td>
              <td>takahashi@mail.com</td>
              <td class="align--right">
                <ul class="list-btn">
                  <li><a href="#" class="btn btn--default btn--small with-icon"><i class="fa fa-eye"></i><span>Detail</span></a></li>
                  <li><a href="#" class="btn btn--danger btn--small with-icon"><i class="fa fa-trash-o"></i><span>Delete</span></a></li>
                </ul>
              </td>
            </tr>
            <tr>
              <td>5</td>
              <td><a href="#">yamada</a></td>
              <td>yamada@mail.com</td>
              <td class="align--right">
                <ul class="list-btn">
                  <li><a href="#" class="btn btn--default btn--small with-icon"><i class="fa fa-eye"></i><span>Detail</span></a></li>
                  <li><a href="#" class="btn btn--danger btn--small with-icon"><i class="fa fa-trash-o"></i><span>Delete</span></a></li>
                </ul>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <script src="./bower_components/jquery/dist/jquery.min.js"></script>
    <script src="./bower_components/adminize/js/adminize.min.js"></script>
  </body>

</html>