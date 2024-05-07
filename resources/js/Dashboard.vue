<template>
    <div class="container p-2">
        <router-link class="fs-1 text-primary text-decoration-none" to="/">
            DASHBOARD
        </router-link>

        <div class="card p-2">
            <nav class="navbar navbar-expand-sm bg-light">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <router-link class="nav-link" :to="'/'">
                            Home
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" :to="'/personal-details'">
                            List
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link
                            class="nav-link"
                            :to="'/create-personal-details'"
                        >
                            Create
                        </router-link>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>
