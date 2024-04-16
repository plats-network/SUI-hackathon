<template>
    <el-row>
        <el-row class="mb-1">
            <h4>Group management</h4>
            <el-button style="float: right;margin: 5px"
                       type="primary"
                       @click="handleCreate()">
                <i class="el-icon-plus"></i> Group Reward
            </el-button>
            <el-descriptions title="" :column="3" border>
                <el-descriptions-item label="Name" label-class-name="my-label" content-class-name="my-content">
                    <el-col :span="23">
                        <el-input placeholder="typing ..." v-model="formSearch.name" @change="list_data()"></el-input>
                    </el-col>
                </el-descriptions-item>
                <el-descriptions-item label="Description" label-class-name="my-label" content-class-name="my-content">
                    <el-col :span="23">
                        <el-input placeholder="typing ..." v-model="formSearch.desc_vn"
                                  @change="list_data()"></el-input>
                    </el-col>
                </el-descriptions-item>
<!--                <el-descriptions-item label="Status" label-class-name="my-label" content-class-name="my-content">-->
<!--                    <el-row :gutter="2">-->
<!--                        <el-col :span="23">-->
<!--                            <el-select class="full-option" @change="list_data()" v-model="formSearch.status"-->
<!--                                       placeholder="Select">-->
<!--                                <el-option-->
<!--                                    v-for="item in [{value: null, label: 'All'},{value: true, label: 'public'}, {value: false, label: 'draft'}]"-->
<!--                                    :key="item.value"-->
<!--                                    :label="item.label"-->
<!--                                    :value="item.value">-->
<!--                                </el-option>-->
<!--                            </el-select>-->
<!--                        </el-col>-->
<!--                    </el-row>-->
<!--                </el-descriptions-item>-->
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
                                :src="scope.row.avatar_url"
                                :preview-src-list="[scope.row.avatar_url]"
                                style="width: 30%; height: auto;"
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
<!--                <el-table-column-->
<!--                    prop="desc_vn"-->
<!--                    label="Description"-->
<!--                    >-->
<!--                </el-table-column>-->
                <el-table-column
                    prop="total_user"
                    label="Total User"
                    width="180">
                </el-table-column>
                <el-table-column
                    label="Status" width="180">
                    <template slot-scope="scope">
                        <el-tag type="success" v-if="scope.row.status === false">draft</el-tag>
                        <el-tag type="danger" v-if="scope.row.status === true">public</el-tag>
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
                            <i class="el-icon-lock"></i>
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
            <el-drawer title="Edit Group" :visible.sync="drawerEdit">
                <el-form ref="form" :rules="rules" :model="formData" label-width="100px" style="padding-right: 20px">
                    <el-form-item label="Image" class="mb-3" prop="image">
                        <el-upload
                            class="avatar-uploader text-center"
                            :headers="{ 'X-CSRF-TOKEN': csrf }"
                            action="/tasks/save-avatar-api"
                            :on-success="handleAvatarSuccess"
                            :before-upload="beforeAvatarUpload">
                            <img v-if="formData.avatar_url" :src="formData.avatar_url" class="avatar">
                            <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                        </el-upload>
                    </el-form-item>
                    <el-form-item label="Name" class="mb-3" prop="name">
                        <el-input v-model="formData.name" placeholder="Name"></el-input>
                    </el-form-item>
                    <el-form-item label="Description VN" class="mb-3" prop="description">
                        <el-input v-model="formData.desc_vn"  :rows="5" type="textarea" placeholder="Description"></el-input>
                    </el-form-item>
                    <el-form-item label="Description EN" class="mb-3" prop="description">
                        <el-input v-model="formData.desc_en" :rows="5"  type="textarea" placeholder="Description"></el-input>
                    </el-form-item>
                    <el-form-item label="Status" prop="region" class="mb-3">
                        <el-select v-model="formData.status" placeholder="Status">
                            <el-option
                                v-for="item in [{value: true, label: 'public'}, {value: false, label: 'draft'}]"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item class="mt-3">
                        <el-button type="primary" @click="submitForm('form')">Edit</el-button>
                    </el-form-item>
                </el-form>
            </el-drawer>
            <el-drawer title="Create Group" :visible.sync="drawerCreate">
                <el-form ref="form" :rules="rules" :model="formData" label-width="100px" style="padding-right: 20px">
                    <el-form-item label="Image" class="mb-3" prop="image">
                        <el-upload
                            class="avatar-uploader text-center"
                            :headers="{ 'X-CSRF-TOKEN': csrf }"
                            action="/tasks/save-avatar-api"
                            :on-success="handleAvatarSuccess"
                            :before-upload="beforeAvatarUpload">
                            <img v-if="formData.avatar_url" :src="formData.avatar_url" class="avatar">
                            <i v-else class="el-icon-plus avatar-uploader-icon"></i>
                        </el-upload>
                    </el-form-item>
                    <el-form-item label="Name VN" class="mb-3" prop="name">
                        <el-input v-model="formData.name" placeholder="Name"></el-input>
                    </el-form-item>
                    <el-form-item label="Name En" class="mb-3" prop="name">
                        <el-input v-model="formData.name_en" placeholder="Name"></el-input>
                    </el-form-item>
                    <el-form-item label="User name" class="mb-3" prop="username">
                        <el-input v-model="formData.username" placeholder="Name"></el-input>
                    </el-form-item>
                    <el-form-item label="Country" class="mb-3" prop="country">
                        <el-input v-model="formData.country" placeholder="Name"></el-input>
                    </el-form-item>
                    <el-form-item label="Description VN" class="mb-3" prop="description">
                        <el-input v-model="formData.desc_vn" :rows="5" type="textarea" placeholder="Description"></el-input>
                    </el-form-item>
                    <el-form-item label="Description EN" class="mb-3" prop="description">
                        <el-input v-model="formData.desc_en" :rows="5" type="textarea" placeholder="Description"></el-input>
                    </el-form-item>
                    <el-form-item label="Status" prop="region" class="mb-3">
                        <el-select v-model="formData.status" placeholder="Status">
                            <el-option
                                v-for="item in [{value: true, label: 'public'}, {value: false, label: 'draft'}]"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value">
                            </el-option>
                        </el-select>
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
    name: "group",
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
                desc_vn: '',
                status: '',
            },
            drawerEdit : false,
            drawerCreate : false,
            formData : {
                id : '',
                avatar_url : '',
                desc_vn : '',
                desc_en : '',
                name : '',
                name_en : '',
                total_user : '',
                username : '',
                country : '',
                status : '',
            },
            rules: {
                avatar_url: [
                    {required: true, message: 'Hãy upload ảnh', trigger: 'change'},
                ],
                name: [
                    {required: true, message: 'Hãy nhập', trigger: 'change'},
                ],
                name_en: [
                    {required: true, message: 'Hãy nhập', trigger: 'change'},
                ],
                country: [
                    {required: true, message: 'Hãy nhập', trigger: 'change'},
                ],
                desc_vn: [
                    {required: true, message: 'Hãy nhập', trigger: 'change'},
                ],
                desc_en: [
                    {required: true, message: 'Hãy nhập', trigger: 'change'},
                ],
            }
        }
    },
    methods: {
        handleDelete(scope, row){
            this.$confirm('Bạn có muốn xóa không ?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Hủy',
                type: 'warning'
            }).then(() => {
                axios.delete('/api/groups/'+row.id, ).then(e => {
                    this.list_data()
                    Notification.success({
                        title: ' Thành công',
                        message: ' Block Thành công',
                        type: 'success',
                    });
                }).catch((_) => {
                })
            }).catch(() => {
            });
        },
        submitForm(form){
            this.$refs[form].validate((valid) => {
                if (valid) {
                    const loading = this.$loading({
                        lock: true,
                        text: 'Loading',
                        spinner: 'el-icon-loading',
                        background: 'rgba(0, 0, 0, 0.7)'
                    });
                    axios.post('/api/groups', this.formData).then(e => {
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
            let url = '/api/groups'
            axios.get(url, {
                params: rawData
            }).then(e => {
                console.log(e.data)
                this.tableData = e.data.data;
                this.totalNumber = e.data.meta.total;
                self.totalItem = e.data.meta.total;
                loading.close();

            }).catch((_) => {
                loading.close();
            })
        },
        handleEdit(scope, row) {
            this.formData = row
            this.drawerEdit = true
        },
        handleCreate() {
            this.formData = {
                id : '',
                avatar_url : '',
                desc_vn : '',
                desc_en : '',
                name : '',
                name_en : '',
                total_user : '',
                username : '',
                country : '',
                status : '',
            }
            this.rules =  {
                avatar_url: [
                    {required: true, message: 'Hãy upload ảnh', trigger: 'change'},
                ],
                name: [
                    {required: true, message: 'Hãy nhập', trigger: 'change'},
                ],
                name_en: [
                    {required: true, message: 'Hãy nhập', trigger: 'change'},
                ],
                country: [
                    {required: true, message: 'Hãy nhập', trigger: 'change'},
                ],
                desc_vn: [
                    {required: true, message: 'Hãy nhập', trigger: 'change'},
                ],
                desc_en: [
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
            this.formData.avatar_url = URL.createObjectURL(file.raw);
            this.formData.avatar_url = res;
        },
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
    mounted: function () {
        this.list_data()
    },
}
</script>

<style scoped>

</style>
