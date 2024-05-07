<script setup>
import { computed, ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import Dashboard from "../../Dashboard.vue";
import defaultImg from "../../../../public/image/profile_pic.png";

const router = useRouter();

const formData = ref({
    profile_img: "",
    fname: "",
    mname: "",
    lname: "",
    birthdate: "",
    address: "",
    gender: "",
    contact: "",
});

const fileInput = ref(null);
const imageUrl = ref(defaultImg);
const triggerFileInput = () => {
    fileInput.value.click();
};

const handleFileUpload = () => {
    // Handle file upload logic here
    console.log("File uploaded");

    const file = fileInput.value.files[0];
    imageUrl.value = URL.createObjectURL(file);
    formData.value.profile_img = file;
};

const isActive = ref(true);
const hasError = ref(false);

const submitPersonalDetails = async () => {
    if (!formData.value.profile_img) {
        formData.value.profile_img = defaultImg;

        console.log(formData.value.profile_img);
    }
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
        const formDataToSend = new FormData();
        Object.entries(formData.value).forEach(([key, value]) => {
            formDataToSend.append(key, value);
        });

        const response = await axios.post(
            "/api/create-personal-details",
            formDataToSend,
            { headers: { "Content-Type": "multipart/form-data" } }
        );

        toast.success("Successfully Created", {
            autoClose: 3000,
        });
        router.push("/personal-details");

        hasError.value = false;
        clearFormData();
    } catch (error) {
        console.error("Error submitting form:", error);
    }

    return {
        fileInput,
        imageUrl,
        triggerFileInput,
        handleFileUpload,
    };
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
                <div
                    class="image mb-2 col-lg-2"
                    id="imageUpload"
                    @click="triggerFileInput"
                >
                    <label for="uploadInput">
                        <img
                            :src="imageUrl"
                            class="img-fluid img-thumbnail"
                            width="200px"
                            height="100px"
                            alt="..."
                        />
                        <br />
                        <label class="ms-5">Upload Image</label>
                    </label>
                    <input
                        type="file"
                        id="uploadInput"
                        class="d-none"
                        name="profile_img"
                        multiple
                        ref="fileInput"
                        @change="handleFileUpload"
                    />
                </div>

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
