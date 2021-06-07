<template>
<div>
    <form @submit.prevent="createAlbum" enctype="multipart/form-data" v-if="!success">
        <div class="form-group">
            <label>Name of Album</label>
            <input type="text" name="name" v-model="name" class="form-control" maxlength="25">
            <span v-if="allErrors.name" :class="['danger']">
                {{ allErrors.name[0] }}
            </span>
        </div>
        <div class="form-group">
            <label>Description of Album</label>
            <textarea class="form-control" maxlength="200" name="description" v-model="description"></textarea>
            <span v-if="allErrors.description" :class="['danger']">
                {{ allErrors.description[0] }}
            </span>
        </div>
        <div class="form-group">
            <label>Category of Album</label>
            <select class="form-control" name="category" v-model="category">
                <option value=''>Please select a category:</option>
                <option v-for="(category,index) in categories" :key="index" :value="category.id">
                    {{category.name}}
                </option>
                <span v-if="allErrors.category" :class="['danger']">
                    {{ allErrors.category[0] }}
                </span>
            </select>
        </div>
        <div class="form-group">
            <label>Image of Album</label>
            <input type="file" name="image" class="form-control" v-on:change="onImageChange">
            <span v-if="allErrors.image" :class="['danger']">
                {{ allErrors.image[0] }}
            </span>
        </div>
        <div class="form-group">
            <button class="btn btn-secondary" type="submit">Create Album</button>
        </div>
    </form>
    <div v-if="success">
        <a :href="'/gallery/'+albumId">Your album is created. Please click the link to upload the images</a>
    </div>
</div>
</template>

<script>
export default {
    data() {
        return {
            name: '',
            description: '',
            category: '',
            image: '',
            categories: [],
            albumId: '',
            success: false,
            allErrors: []
        }
    },
    created() {
        this.getCategories()
    },
    methods: {
        getCategories() {
            axios.get('/api/categories').then((response) => {
                this.categories = response.data
            }).catch((error) => {
                alert('unable to fetch categories')
            })
        },
        onImageChange(e) {
            console.log(e)
            this.image = e.target.files[0]; // u console ovako dolazimo do slike
        },
        createAlbum() {
            const config = {
                headers: {
                    "content-type": "multipart/form-data"
                }
            }
            let formData = new FormData();
            formData.append('image', this.image);
            formData.append('name', this.name);
            formData.append('description', this.description);
            formData.append('category_id', this.category);
            axios.post('/albums/store', formData, config).then((response) => {
                this.image = '',
                this.name = '',
                this.description = '',
                this.category = ''
                this.albumId = response.data.id
                console.log(response.data.id)
                this.success = true
            }).catch((error) => {
                console.log(error)
                this.allErrors = error.response.data.errors
            })
        }
    }
}
</script>

<style>
.danger {
    color: red;
}
</style>
