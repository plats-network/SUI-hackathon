<template>
    <el-row>
        <el-row class="mb-1">
            <h4>Reward management</h4>
            <el-button style="float: right;margin: 5px"
                       type="primary"
                       @click="handleCreate()">
                <i class="el-icon-plus"></i> Create Reward
            </el-button>
            <el-descriptions title="" :column="3" border>
                <el-descriptions-item label="Name" label-class-name="my-label" content-class-name="my-content">
                    <el-col :span="23">
                        <el-input placeholder="typing ..." v-model="formSearch.name" @change="list_data()"></el-input>
                    </el-col>
                </el-descriptions-item>
                <el-descriptions-item label="Description" label-class-name="my-label" content-class-name="my-content">
                    <el-col :span="23">
                        <el-input placeholder="typing ..." v-model="formSearch.description"
                                  @change="list_data()"></el-input>
                    </el-col>
                </el-descriptions-item>
                <el-descriptions-item label="Status" label-class-name="my-label" content-class-name="my-content">
                    <el-row :gutter="2">
                        <el-col :span="23">
                            <el-select class="full-option" @change="list_data()" v-model="formSearch.status"
                                       placeholder="Select">
                                <el-option
                                    v-for="item in [{value: null, label: 'All'},{value: '1', label: 'public'}, {value: '0', label: 'draft'}]"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                                </el-option>
                            </el-select>
                        </el-col>
                    </el-row>
                </el-descriptions-item>
            </el-descriptions>
        </el-row>
        <el-row>
            <el-table
                :data="tableData"
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
                                :src="scope.row.image"
                                :preview-src-list="[scope.row.image]"
                                style="width: 35%;"
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
                    prop="description"
                    label="Description"
                >
                </el-table-column>
                <el-table-column
                    label="Status" width="180">
                    <template slot-scope="scope">
                        <el-tag type="success" v-if="scope.row.status == '0'">draft</el-tag>
                        <el-tag type="danger" v-if="scope.row.status == '1'">public</el-tag>
                    </template>
                </el-table-column>
                <el-table-column
                    label="Actions">
                    <template slot-scope="scope">
                        <el-button
                            size="mini"
                            type="primary"
                            @click="handleEdit(scope.$index, scope.row)">
                            <i class="el-icon-edit"></i>
                        </el-button>
                        <el-button
                            size="mini"
                            type="danger"
                            @click="handleDelete(scope.$index, scope.row)">
                            <i class="el-icon-delete-solid"></i>
                        </el-button>
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
            <el-drawer title="Edit Reward" :visible.sync="drawerEdit">
                <el-form ref="form" :rules="rules" :model="formData" label-width="100px" style="padding-right: 20px">
                    <el-form-item label="Image" class="mb-3" prop="image">
                        <el-upload
                            class="avatar-uploader text-center"
                            :headers="{ 'X-CSRF-TOKEN': csrf }"
                            action="/tasks/save-avatar-api"
                            :on-success="handleAvatarSuccess"
                            :before-upload="beforeAvatarUpload">
                            <img v-if="formData.image" :src="formData.image" class="avatar">
                            <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                        </el-upload>
                    </el-form-item>
                    <el-form-item label="Name" class="mb-3" prop="name">
                        <el-input v-model="formData.name" placeholder="Name"></el-input>
                    </el-form-item>
                    <el-form-item label="Description" class="mb-3"  prop="description">
                        <el-input v-model="formData.description"  type="textarea" :rows="5" placeholder="Description"></el-input>
                    </el-form-item>
                    <el-form-item label="Status" prop="region" class="mb-3">
                        <el-select v-model="formData.status" placeholder="Status">
                            <el-option
                                v-for="item in [{value: '1', label: 'public'}, {value: '0', label: 'draft'}]"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="Order" class="mb-2" prop="order">
                        <el-input v-model="formData.order" placeholder="Order"></el-input>
                    </el-form-item>
                    <el-form-item class="mt-3">
                        <el-button type="primary" @click="submitForm('form')">Edit</el-button>
                    </el-form-item>
                </el-form>
            </el-drawer>
            <el-drawer title="Create Reward" :visible.sync="drawerCreate">
                <el-form ref="form" :rules="rules" :model="formData" label-width="100px" style="padding-right: 20px">
                    <el-form-item label="Image" class="mb-3" prop="image">
                        <el-upload
                            class="avatar-uploader text-center"
                            :headers="{ 'X-CSRF-TOKEN': csrf }"
                            action="/tasks/save-avatar-api"
                            :on-success="handleAvatarSuccess"
                            :before-upload="beforeAvatarUpload">
                            <img v-if="formData.image" :src="formData.image" class="avatar">
                            <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                        </el-upload>
                    </el-form-item>
                    <el-form-item label="Name" class="mb-3" prop="name">
                        <el-input v-model="formData.name" placeholder="Name"></el-input>
                    </el-form-item>
                    <el-form-item label="Description" class="mb-3"  prop="description">
                        <el-input v-model="formData.description" :rows="5"  type="textarea" placeholder="Description"></el-input>
                    </el-form-item>
                    <el-form-item label="Status" prop="region" class="mb-3">
                        <el-select v-model="formData.status" placeholder="Status">
                            <el-option
                                v-for="item in [{value: 1, label: 'public'}, {value: 0, label: 'draft'}]"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="Order" class="mb-3" prop="order">
                        <el-input v-model="formData.order" placeholder="Order"></el-input>
                    </el-form-item>
                    <el-form-item class="mt-3">
                        <el-button type="primary" @click="submitForm('form')">Create</el-button>
                    </el-form-item>
                </el-form>
            </el-drawer>
        </el-row>
    </el-row>
</template>

<script>
import 'element-tiptap/lib/index.css';
import {Notification} from 'element-ui';
import {ElementTiptap} from 'element-tiptap';
import {
    // necessary extensions
    Doc,
    Text,
    Paragraph,
    Heading,
    Bold,
    Underline,
    Italic,
    Strike,
    ListItem,
    BulletList,
    OrderedList,
    Image,
    TodoList,
    TodoItem,
    Iframe,
    Table,
    TableHeader,
    TableRow,
    TableCell,
    TextAlign,
    LineHeight,
    Indent,
    HorizontalRule,
    HardBreak,
    TrailingNode,
    History,
    TextColor,
    TextHighlight,
    FormatClear,
    FontType,
    FontSize,
    Preview,
    CodeView,
    Print,
    Fullscreen,
    SelectAll,
} from 'element-tiptap';

export default {
    name: "index",
    props: ['csrf'],
    components: {
        'el-tiptap': ElementTiptap,
    },
    data() {
        return {
            input: '',
            tableData: [],
            currentPage: 1,
            totalNumber: 0,
            page: 1,
            formSearch: {
                name: '',
                description: '',
                status: '',
            },
            drawerEdit : false,
            drawerCreate : false,
            formData : {
                id : '',
                image : '',
                description : '',
                name : '',
                order : '',
                status : '',
            },
            rules: {
                image: [
                    {required: true, message: 'Hãy upload ảnh', trigger: 'change'},
                ],
                name: [
                    {required: true, message: 'Hãy nhập', trigger: 'change'},
                ],
                description: [
                    {required: true, message: 'Hãy nhập', trigger: 'change'},
                ],
                order: [
                    {required: true, message: 'Hãy nhập', trigger: 'change'},
                ],
            }
        }
    },
    methods: {
        // Delete record
        handleDelete(scope, row){
            this.$confirm('Bạn có muốn xóa không ?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Hủy',
                type: 'warning'
            }).then(() => {
                axios.get('/api/rewards/delete/'+row.id, ).then(e => {
                    this.list_data()
                }).catch((_) => {
                })
            }).catch(() => {
            });
        },

        // Submit form
        submitForm(form){
            this.$refs[form].validate((valid) => {
                if (valid) {
                    const loading = this.$loading({
                        lock: true,
                        text: 'Loading',
                        spinner: 'el-icon-loading',
                        background: 'rgba(0, 0, 0, 0.7)'
                    });
                    axios.post('/api/rewards/store', this.formData).then(e => {
                        Notification.success({
                            title: ' Thành công',
                            message: ' Thành công',
                            type: 'success',
                        });
                        this.list_data()
                        loading.close();
                        this.drawerCreate = false
                        this.drawerEdit = false
                    }).catch(error => {
                        this.errors = error.response.data.message; // this should be errors.
                        Notification.error({
                            title: 'Error',
                            message: this.errors,
                            type: 'error',
                        });
                        loading.close();
                    });
                    console.log(this.form)
                } else {
                    console.log('error submit!!');
                    return false;
                }
            });
        },

        // List data
        list_data(val = 1, type = true) {
            var self = this;
            let rawData =
                {
                    'name': this.formSearch.name,
                    'description': this.formSearch.description,
                    'status': this.formSearch.status
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
            let url = '/api/rewards'
            axios.get(url, {
                params: rawData
            }).then(e => {
                console.log(e.data)
                this.tableData = e.data.data.data;
                this.totalNumber = e.data.data.total;
                self.totalItem = e.data.data.to;
                loading.close();

            }).catch((_) => {
                loading.close();
            })
        },

        // Edit data
        handleEdit(scope, row) {
            this.formData = row
            this.drawerEdit = true

            this.$refs[form].validate((valid) => {
                if (valid) {
                    const loading = this.$loading({
                        lock: true,
                        text: 'Loading',
                        spinner: 'el-icon-loading',
                        background: 'rgba(0, 0, 0, 0.7)'
                    });
                    this.formData.id = row.id
                    axios.post('/api/rewards/store', this.formData).then(e => {
                        Notification.success({
                            title: ' Thành công',
                            message: ' Thành công',
                            type: 'success',
                        });
                        this.drawerEdit = false
                        this.list_data()
                        loading.close();
                    }).catch(error => {
                        this.errors = error.response.data.message; // this should be errors.
                        Notification.error({
                            title: 'Error',
                            message: this.errors,
                            type: 'error',
                        });
                        loading.close();
                    });
                    console.log(this.form)
                } else {
                    console.log('error submit!!');
                    return false;
                }
            });
        },

        // Handle Create
        handleCreate() {
            this.formData = {
                    image : '',
                    description : '',
                    name : '',
                    order : '',
                    status : 0,
            }
            this.rules =  {
                image: [
                    {required: true, message: 'Hãy upload ảnh', trigger: 'change'},
                ],
                    name: [
                    {required: true, message: 'Hãy nhập', trigger: 'change'},
                ],
                    description: [
                    {required: true, message: 'Hãy nhập', trigger: 'change'},
                ],
                    order: [
                    {required: true, message: 'Hãy nhập', trigger: 'change'},
                ],
            }
           this.drawerCreate = true
        },

        handleSizeChange(val) {
            this.list_data(val, false);
        },
        handleCurrentChange(val) {
            this.list_data(val);
        },
        handleAvatarSuccess(res, file) {
            this.formData.image = URL.createObjectURL(file.raw);
            this.formData.image = res;
        },

        // Check Upload image
        beforeAvatarUpload(file) {
            const isJPEG = file.type === 'image/jpeg';
            const isPNG = file.type === 'image/png';
            const isJPG = file.type === 'image/jpg';

            const isLt10M = file.size / 1024 / 1024 < 10;

            if (!isJPEG && !isPNG && !isJPG) {
                this.$message.error('Chọn ảnh định dạng JPG,PNG,JPEG!');
                return false
            }
            if (!isLt10M) {
                this.$message.error('Avatar picture size can not exceed 2MB!')
                return false
            }
            return isLt10M;
        },
    },

    // Mount
    mounted: function () {
        this.list_data()
    },
}
</script>

<style scoped>

</style>
