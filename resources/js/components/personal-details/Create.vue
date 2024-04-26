<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import Dashboard from "../../Dashboard.vue";

const router = useRouter();

const formData = ref({
    fname: "",
    mname: "",
    lname: "",
    birthdate: "",
    address: "",
    gender: "",
    contact: "",
});

const isActive = ref(true);
const hasError = ref(false);

const submitPersonalDetails = async () => {
    if (
        !formData.value.fname ||
        !formData.value.lname ||
        !formData.value.birthdate ||
        !formData.value.address ||
        !formData.value.gender ||
        !formData.value.contact
    ) {
        toast.error("Do not leave the field blanks.", {
            autoClose: 10000,
        });
        hasError.value = true;
    }

    const clearFormData = () => {
        formData.value.fname = "";
        formData.value.mname = "";
        formData.value.lname = "";
        formData.value.birthdate = "";
        formData.value.address = "";
        formData.value.gender = "";
        formData.value.contact = "";
    };

    try {
        const response = await axios.post(
            "/api/create-personal-details",
            formData.value
        );
        // alert(response.data.message);
        // await router.push({name: 'personal.detail.lists'})
        toast.success("Successfully Created", {
            autoClose: 3000,
        });

        hasError.value = false;
        clearFormData();
    } catch (error) {
        console.error("Error submitting form:", error);
    }
};
</script>

<template>
    <Dashboard />
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                PERSONAL DETAILS
            </div>
            <div class="card-body">
                <div class="form-group mb-2">
                    <label for="">First Name</label>
                    <input
                        type="text"
                        :class="{
                            'form-control': isActive,
                            'is-invalid': hasError,
                        }"
                        name="first_name"
                        v-model="formData.fname"
                    />
                </div>
                <div class="form-group mb-2">
                    <label for="">Middle Name (Optional)</label>
                    <input
                        type="text"
                        :class="{
                            'form-control': isActive,
                            'is-invalid': hasError,
                        }"
                        name="middle_name"
                        v-model="formData.mname"
                    />
                </div>
                <div class="form-group mb-2">
                    <label for="">Last Name</label>
                    <input
                        type="text"
                        :class="{
                            'form-control': isActive,
                            'is-invalid': hasError,
                        }"
                        name="last_name"
                        v-model="formData.lname"
                    />
                </div>
                <div class="form-group mb-2">
                    <label for="">Birth Date</label>
                    <input
                        type="date"
                        :class="{
                            'form-control': isActive,
                            'is-invalid': hasError,
                        }"
                        name="birthdate"
                        v-model="formData.birthdate"
                    />
                </div>
                <div class="form-group mb-2">
                    <label for="">Address</label>
                    <textarea
                        type="text"
                        :class="{
                            'form-control': isActive,
                            'is-invalid': hasError,
                        }"
                        name="address"
                        v-model="formData.address"
                    >
                    </textarea>
                </div>
                <div class="form-group mb-2">
                    <div>
                        <label for="">Gender</label>
                    </div>
                    <div class="card p-2">
                        <div class="form-check form-check-inline">
                            <input
                                :class="{
                                    'form-check-input': isActive,
                                    'is-invalid': hasError,
                                }"
                                type="radio"
                                name="gender"
                                value="male"
                                v-model="formData.gender"
                            />
                            <label
                                class="form-check-label"
                                for="flexRadioDefault1"
                                >Male</label
                            >
                        </div>
                        <div class="form-check form-check-inline">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="gender"
                                value="female"
                                v-model="formData.gender"
                            />
                            <label
                                :class="{
                                    'form-check-label': isActive,
                                    'is-invalid': hasError,
                                }"
                                for="flexRadioDefault2"
                                >Female</label
                            >
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="">Contact No.</label>
                    <input
                        type="number"
                        :class="{
                            'form-control': isActive,
                            'is-invalid': hasError,
                        }"
                        name="contact"
                        v-model="formData.contact"
                    />
                </div>
                <div class="float-end">
                    <router-link
                        class="btn btn-secondary me-2"
                        :to="'/personal-details'"
                    >
                        Lists
                    </router-link>
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="submitPersonalDetails"
                    >
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
