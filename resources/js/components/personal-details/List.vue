<script setup>
import { ref, reactive, onMounted } from "vue";
import axios from "axios";
import { useRoute, useRouter } from "vue-router";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import Dashboard from "../../Dashboard.vue";
import Search from "@/components/personal-details/Search.vue";

const modal = ref(null);
const modalImg = ref(null);
const captionText = ref(null);

const onClick = function (imageUrl, altText) {
    modal.value.style.display = "block";
    modalImg.value.src = `/image/${imageUrl}`;
    captionText.value.innerHTML = altText;
};

const closeModal = function () {
    modal.value.style.display = "none";
};

const router = useRouter();
const route = useRoute();

let personalDetails = ref([]);

let meta = ref({ current_page: 1, links: [] });

const fetchDetails = async (url) => {
    if (url) {
        try {
            const response = await axios.get(url);
            personalDetails.value = response.data.data;
            meta.value.current_page = response.data.current_page;
            meta.value.links = response.data.links;
            meta.value.per_page = response.data.per_page;

            if (!response.data.from && response.data.total !== 0) {
                fetchDetails(
                    `/api/personal-details?page=${meta.value.current_page - 1}`
                );
            }
        } catch (error) {
            console.error("Error fetching personal details:", error);
        }
    }
};

onMounted(() => {
    fetchDetails(`/api/personal-details?page=${route.query.page}` || "");
});

const deleteDetail = async (id) => {
    try {
        await axios.delete(`/api/delete-personal-details/${id}`);
        toast.success("Successfully deleted!", {
            autoClose: 2000,
        });

        // Remove the deleted detail from the personalDetails array
        const index = personalDetails.value.findIndex(
            (detail) => detail.id === id
        );

        if (index !== -1) {
            personalDetails.value.splice(index, 1);
            fetchDetails(
                `/api/personal-details/?page=${meta.value.current_page}`
            );
        }
        await router.push({ name: "personal.details" });
    } catch (error) {
        console.error("Error deleting personal detail:", error);
    }
};

const onSearchFinished = (data) => {
    personalDetails.value = data.data;
    meta.value.current_page = data.current_page;
    meta.value.links = data.links;
    meta.value.per_page = data.per_page;

    if (!data.from && data.total !== 0) {
        fetchDetails(
            `/api/personal-details?page=${meta.value.current_page - 1}`
        );
    }
};
</script>

<template>
    <Dashboard />
    <div class="container p-2">
        <div class="card mt-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between col-lg-12 mb-4">
                    <div class="col-lg-6">
                        <Search @results="onSearchFinished" />
                    </div>
                    <div class="col-lg-6">
                        <router-link
                            class="btn btn-primary btn-md float-end"
                            :to="'/create-personal-details'"
                            >Create New</router-link
                        >
                    </div>
                </div>
                <div id="myModal" class="modal" ref="modal">
                    <span class="close" @click="closeModal">&times;</span>
                    <img class="modal-content" id="img01" ref="modalImg" />
                    <div id="caption" ref="captionText"></div>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead class="text-center">
                            <tr>
                                <th>No.</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Last Name</th>
                                <th>Birthdate</th>
                                <th>Address</th>
                                <th>Gender</th>
                                <th>Contact</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="personalDetails.length === 0">
                                <tr>
                                    <td colspan="9" class="text-center">
                                        No Data
                                    </td>
                                </tr>
                            </template>

                            <template v-else>
                                <tr
                                    v-for="(detail, index) in personalDetails"
                                    :key="detail.id"
                                >
                                    <td>
                                        {{
                                            (meta.current_page - 1) *
                                                meta.per_page +
                                            index +
                                            1
                                        }}
                                    </td>
                                    <td>{{ detail.first_name }}</td>
                                    <td>{{ detail.middle_name }}</td>
                                    <td>{{ detail.last_name }}</td>
                                    <td>{{ detail.birthdate }}</td>
                                    <td>{{ detail.address }}</td>
                                    <td>{{ detail.gender }}</td>
                                    <td>{{ detail.contact_no }}</td>
                                    <td v-if="detail.profile_img">
                                        <img
                                            :src="detail.profile_img"
                                            width="50px"
                                            height="50px"
                                            @click="
                                                () =>
                                                    onclick(
                                                        detail.profile_img,
                                                        detail.alt
                                                    )
                                            "
                                        />
                                    </td>
                                    <td>
                                        <router-link
                                            class="btn btn-info btn-sm me-1"
                                            :to="`/edit-personal-details/${detail.id}/${meta.current_page}`"
                                        >
                                            Edit
                                        </router-link>
                                        <button
                                            type="button"
                                            class="btn btn-danger btn-sm"
                                            @click="
                                                () => deleteDetail(detail.id)
                                            "
                                        >
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li
                                class="page-item"
                                v-for="page in meta.links"
                                :key="page"
                            >
                                <a
                                    class="page-link"
                                    :class="{
                                        active: page.label == meta.current_page,
                                    }"
                                    @click="fetchDetails(page.url)"
                                    href="#"
                                    v-html="page.label"
                                >
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
body {
    font-family: Arial, Helvetica, sans-serif;
}

#myImg {
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

#myImg:hover {
    opacity: 0.7;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1;
    padding-top: 100px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgb(0, 0, 0);
    background-color: rgba(0, 0, 0, 0.9);
}

/* Modal Content (image) */
.modal-content {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
}

/* Caption of Modal Image */
#caption {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
    text-align: center;
    color: #ccc;
    padding: 10px 0;
    height: 150px;
}

/* Add Animation */
.modal-content,
#caption {
    -webkit-animation-name: zoom;
    -webkit-animation-duration: 0.6s;
    animation-name: zoom;
    animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
    from {
        -webkit-transform: scale(0);
    }
    to {
        -webkit-transform: scale(1);
    }
}

@keyframes zoom {
    from {
        transform: scale(0);
    }
    to {
        transform: scale(1);
    }
}

/* The Close Button */
.close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
}

.close:hover,
.close:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px) {
    .modal-content {
        width: 100%;
    }
}
</style>
