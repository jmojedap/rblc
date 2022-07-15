<div class="nav2-sticky">
    <ul id="nav_2" class="nav_2 nav nav-pills nav-fill mb-4">
        <li class="nav-item" v-for="(menu, menu_key) in menu_elements">
            <a class="nav-link" href="#" v-on:click="set_category(menu_key)" v-bind:class="{'active': menu.slug == category.slug }">
                {{ menu.text }}
            </a>
        </li>
    </ul>
</div>
