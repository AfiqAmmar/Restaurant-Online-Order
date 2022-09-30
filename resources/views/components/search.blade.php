@props(['customer_id'])

<button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
  aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
  <i class="fas fa-search"></i>
</button>
<div class="collapse navbar-collapse py-2" id="navbarNav">
  <form class="d-flex" role="search" action="/{{$customer_id}}/menus">
    <input class="form-control me-2" type="search" placeholder="Search menus..." id="" aria-label="Search"
      name="search">
    <button class="btn btn-outline-success" type="submit">Search</button>
  </form>
</div>