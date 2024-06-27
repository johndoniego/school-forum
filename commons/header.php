<link rel="stylesheet" href="css/sidebar.css">
<link rel="stylesheet" href="css/header.css">
<style>
    #topnav {
    background-color: white;
    color: white;
    padding: 10px 0;
    border-bottom: 2px solid grey;
}

#topnav ul {
    display: flex;
    justify-content: flex-end;
    list-style-type: none;
    padding: 0;
    margin: 0;
}

#topnav ul li {
    margin-left: 20px;
    color: white;
}

.search-bar {
    margin-bottom: 20px;
}

.search-bar {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
}

.search-bar form {
    display: flex;
}

.search-bar input[type="text"] {
    padding: 5px;
    margin-right: 10px;
    border: 2px solid grey;
    border-radius: 5px;
}

.search-bar button {
    padding: 5px 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

nav {
    flex-grow: 2;
    text-align: right;
}


#topnav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
}

#topnav .inner {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

#topnav .logo {
    flex-grow: 1;
}

nav ul {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
}

nav ul li {
    margin-left: 20px;
}

nav ul li a {
    text-decoration: none;
    color: #000;
}

hr {
    margin: 0;
    border: 0;
    border-top: 2px solid grey;
    width: 80%;
}

.sticky-header {
    position: -webkit-sticky;
    /* For Safari */
    position: sticky;
    top: 0;
    z-index: 999;
    /* Ensure the header is above other content */
    background-color: #fff;
    /* Background color of the header */
    border-bottom: 1px solid #e0e0e0;
    /* Optional: adds a bottom border */
}

</style>
<header class="sticky-header">
        <div id="topnav">
            <div class="inner">
                <nav role='navigation'>
                    <!-- Search Bar Added Below -->
                    <div class="search-bar">
                        <form action="/search" method="get">
                            <input type="text" name="query" placeholder="Search...">
                            <button type="submit">Search</button>
                        </form>
                    </div>
                    <ul>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPostModal">Create
                            Post</button>
                        <li><a href="#"><img class="user-icon" name="user-icon" src="assets/img/placeholder.png" alt="user"></a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>