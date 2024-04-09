<template>
    <el-row>
        <el-row class="mb-1">
            <h4>User management</h4>
            <el-descriptions title="" :column="3" border>
                <el-descriptions-item label="Name" label-class-name="my-label" content-class-name="my-content">
                    <el-col :span="23">
                        <el-input placeholder="typing ..." v-model="formSearch.name" @change="list_data()"></el-input>
                    </el-col>
                </el-descriptions-item>
                <el-descriptions-item label="Email" label-class-name="my-label" content-class-name="my-content">
                    <el-col :span="23">
                        <el-input placeholder="typing ..." v-model="formSearch.email"
                                  @change="list_data()"></el-input>
                    </el-col>
                </el-descriptions-item>
                <el-descriptions-item label="Role" label-class-name="my-label" content-class-name="my-content">
                    <el-row :gutter="2">
                        <el-col :span="23">
                            <el-select class="full-option" @change="list_data()" v-model="formSearch.status"
                                       placeholder="Select">
                                <el-option
                                    v-for="item in [{value: null, label: 'All'},{value: 1, label: 'User'}, {value: 2, label: 'Admin'}, {value: 3, label: ' Client'}]"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                                </el-option>
                            </el-select>
                        </el-col>
                    </el-row>
                </el-descriptions-item>
                <el-descriptions-item label="Date to *" label-class-name="my-label" content-class-name="my-content">
                    <el-col :span="23">
                        <el-date-picker @change="list_data()"
                                        v-model="formSearch.date_to"
                                        type="date"
                                        placeholder="Pick a day">
                        </el-date-picker>
                    </el-col>
                </el-descriptions-item>
                <el-descriptions-item label="Date from *" label-class-name="my-label" content-class-name="my-content">
                    <el-col :span="23">
                        <el-date-picker @change="list_data()"
                            v-model="formSearch.date_end"
                            type="date"
                            placeholder="Pick a day">
                        </el-date-picker>
                    </el-col>
                </el-descriptions-item>
            </el-descriptions>
        </el-row>
        <el-row>
            <el-table
                :data="formData"
                style="width: 100%">
                <el-table-column
                    type="index"
                    width="50">
                </el-table-column>
                <el-table-column
                    label="Image"
                    width="180">
                    <template slot-scope="scope">
                        <div class="demo-image__preview">
                            <el-image
                                :src="scope.row.avatar_path"
                                :preview-src-list="[scope.row.avatar_path]"
                            >
                            </el-image>
                        </div>
                    </template>

                </el-table-column>
                <el-table-column
                    prop="name"
                    label="Name"
                    >
                </el-table-column>
                <el-table-column
                    prop="email"
                    label="Email"
                    >
                </el-table-column>
                <el-table-column
                    label="Status">
                    <template slot-scope="scope">
                        <el-tag type="success" v-if="scope.row.role == 1">User</el-tag>
                        <el-tag type="success" v-if="scope.row.role == 2">Admin</el-tag>
                        <el-tag type="success" v-if="scope.row.role == 3">Client</el-tag>
                    </template>
                </el-table-column>
                <el-table-column
                    prop="created_at"
                    label="Create"
                    >
                    <template slot-scope="scope">
                        <span>{{ scope.row.created_at | moment }}</span>
                    </template>
                </el-table-column>
            </el-table>
            <el-pagination
                background
                @size-change="handleSizeChange"
                @current-change="handleCurrentChange"
                :page-size="10"
                :current-page.sync="currentPage"
                layout="prev, pager, next"
                :total="totalNumber"
                class="float-right mt-3 text-center"
            >
            </el-pagination>
        </el-row>
    </el-row>
</template>

<script>
import {ElementTiptap} from "element-tiptap";
import moment from "moment";
import {Notification} from "element-ui";

export default {
    name: "index",
    props: ['csrf'],
    components: {
        'el-tiptap': ElementTiptap,
    },
    data() {
        return {
            currentPage: 1,
            totalNumber: 0,
            page: 1,
            formSearch: {
                name: '',
                role: '',
                email: '',
                date_to: '',
                date_end: '',
            },
            formData : [
                {
                    id : '',
                    avatar_path : '',
                    role : '',
                    name : '',
                    email : '',
                    created_at : '',
                }
            ],
        }
    },
    methods: {
        list_data(val = 1, type = true) {
            var self = this;
            let rawData =
                {
                    'name': this.formSearch.name,
                    'email': this.formSearch.email,
                    'role': this.formSearch.role,
                    'date_to': this.formSearch.date_to,
                    'date_end': this.formSearch.date_end,
                }
            if (!type) {
                rawData['size'] = val;
            } else {
                this.page = val;
                rawData['page'] = val;
            }
            rawData['page'] = val;
            const loading = this.$loading({
                lock: true,
                text: 'Loading',
                spinner: 'el-icon-loading',
                background: 'rgba(0, 0, 0, 0.7)'
            });
            let url = '/users/list'
            axios.get(url, {
                params: rawData
            }).then(e => {
                console.log(e.data)
                this.formData = e.data.data;
                this.totalNumber = e.data.total;
                self.totalItem = e.data.total;
                loading.close();

            }).catch((_) => {
                loading.close();
            })
        },
        handleSizeChange(val) {
            this.list_data(val, false);
        },
        handleCurrentChange(val) {
            this.list_data(val);
        },
    },
    mounted: function () {
        this.list_data()
    },
    filters: {
        moment: function (date) {
            return moment(date).format('YYYY-MM-DD');
        }
        ,

    }
}
</script>

<style scoped>

</style>
