<template>
    <el-row>
        <el-row class="mb-1">
            <h4>Events management</h4>
            <el-button style="float: right;margin: 5px"
                       type="primary"
                       @click="create()">
                <i class="el-icon-plus"></i> Create Event
            </el-button>
            <el-descriptions title="" :column="3" border>
                <el-descriptions-item label="Name" label-class-name="my-label" content-class-name="my-content">
                    <el-col :span="23">
                        <el-input placeholder="typing ..." v-model="formSearch.name" @change="list_data()"></el-input>
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
                <el-table-column type="index" width="50"></el-table-column>
                <el-table-column
                    label="Image"
                    width="180">
                    <template slot-scope="scope">
                        <div class="demo-image__preview">
                            <el-image
                                :src="scope.row.banner_url"
                                :preview-src-list="[scope.row.banner_url]"
                                style="width: 50%"
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
                    label="Thời gian" width="180"
                >
                    <template slot-scope="scope">
                        <el-tag type="info" v-if="new Date(scope.row.end_at) < new Date()" effect="dark">
                            Hết Hạn
                        </el-tag>
                        <el-tag type="danger" v-else effect="dark">
                            Còn Hạn
                        </el-tag>
                    </template>
                </el-table-column>
                <el-table-column  width="180"
                    label="Status">
                    <template slot-scope="scope">
                        <el-switch
                            v-model="scope.row.status"
                            :active-value="1"
                            @change="changeStatus(scope.row.status,scope.row.id)"
                            :inactive-value="0"
                            active-text="public"
                            inactive-text="draft"
                        >
                        </el-switch>
                    </template>
                </el-table-column>
                <el-table-column
                    label="Actions">
                    <template slot-scope="scope">
                        <el-tooltip class="item" effect="dark" content="Preview" placement="top">
                            <el-button
                                size="mini"
                                @click="handlePreviewEvent(scope.$index, scope.row)">
                                <i class="el-icon-edit"></i>
                            </el-button>
                        </el-tooltip>

                        <el-tooltip class="item" effect="dark" content="Lấy mã QR" placement="top">
                            <el-button
                                size="mini" type="warning"
                                @click="handleQrCode(scope.$index, scope.row)">
                                <i class="el-icon-view"></i>
                            </el-button>
                        </el-tooltip>

                        <el-tooltip class="item" v-if="scope.row.task_generate_links.length != 0" effect="dark" content="Link Share" placement="top">
                        <el-button
                            size="mini"
                            type="success"
                            @click="handleLink(scope.$index, scope.row)">
                            <i class="el-icon-paperclip"></i>
                        </el-button>
                        </el-tooltip>
                        <el-tooltip v-if="scope.row.user_get_tickets.length != 0" class="item" effect="dark" content="Danh sách tham gia event" placement="top">
                                <el-button
                                    size="mini" plain  type="success"
                                    @click="handleJoinEvent(scope.$index, scope.row)">
                                    {{ scope.row.user_get_tickets.length}}&nbsp;<i class="el-icon-s-custom"></i>
                                </el-button>
                        </el-tooltip>
<!--                            <el-tooltip class="item" effect="dark" content="Lấy mã QR" placement="top">-->
<!--                        <el-button-->
<!--                            size="mini"-->
<!--                            type="danger"-->
<!--                            @click="handleDelete(scope.$index, scope.row)">-->
<!--                            <i class="el-icon-lock"></i>-->
<!--                        </el-button>-->
<!--                            </el-tooltip>-->
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
            <!-- Qrcode view -->
            <el-dialog  title="Link QrCode" :visible.sync="dialogQrCode" style="height: auto">
                <el-tabs v-model="activeName" @tab-click="handleClickTab">
                    <el-tab-pane label="Sessions" name="first">
                        <el-table border
                                  :data="dataSessions"
                                  style="width: 100%">
                            <el-table-column
                                type="index"
                                width="50">
                            </el-table-column>
                            <el-table-column
                                prop="name"
                                label="Name"
                            >
                            </el-table-column>
                            <el-table-column
                                label="QRCODE"
                            >
                                <template slot-scope="scope">
                                    <div ref="qrcode">
                                    <qrcode-vue :id="scope.row.id" :value="link_event+'/events/code?type=event&id='+scope.row.code" :size="size" level="H"></qrcode-vue>
                                    </div>
                                    <button @click="downloadQrCode(scope.row.id)">Download</button>
                                </template>
                            </el-table-column>
                            <el-table-column label="Status" width="180">
                                <template slot-scope="scope">
                                    <el-switch
                                        v-model="scope.row.status"
                                        :active-value="true"
                                        @change="changeStatusDetail(scope.row.status,scope.row.id)"
                                        :inactive-value="false"
                                        active-text="UnLock"
                                        inactive-text="Lock"
                                    >
                                    </el-switch>
                                </template>
                            </el-table-column>
                        </el-table>
                    </el-tab-pane>
                    <el-tab-pane label="Booths" name="second">
                        <el-table border
                                  :data="dataBooths"
                                  style="width: 100%">
                            <el-table-column
                                type="index"
                                width="50">
                            </el-table-column>
                            <el-table-column
                                prop="name"
                                label="Name"
                            >
                            </el-table-column>
                            <el-table-column
                                label="QRCODE"
                            >
                                <template slot-scope="scope">
                                    <div ref="qrcode">
                                        <qrcode-vue :id="scope.row.id" :value="link_event+'/events/code?type=event&id='+scope.row.code" :size="size" level="H"></qrcode-vue>
                                    </div>
                                    <button @click="downloadQrCode(scope.row.id)">Download</button>
                                </template>
                            </el-table-column>
                            <el-table-column label="Status" width="180">
                                <template slot-scope="scope">
                                    <el-switch
                                        v-model="scope.row.status"
                                        :active-value="true"
                                        @change="changeStatusDetail(scope.row.status,scope.row.id)"
                                        :inactive-value="false"
                                        active-text="UnLock"
                                        inactive-text="Lock"
                                    >
                                    </el-switch>
                                </template>
                            </el-table-column>
                        </el-table>
                    </el-tab-pane>
                </el-tabs>
            </el-dialog>
<!--            link share-->
            <el-dialog title="Link Share" :visible.sync="dialogLinks">
                <el-table :data="dataLink">
                    <el-table-column property="name" label="Name" width="200"></el-table-column>
                    <el-table-column property="url" label="Url" ></el-table-column>
                </el-table>
            </el-dialog>
        </el-row>
    </el-row>
</template>

<script>
import { ref } from 'vue'
import 'element-tiptap/lib/index.css';
import {Notification} from 'element-ui';
import {ElementTiptap} from 'element-tiptap';
import QrcodeVue from 'qrcode.vue'
import {
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
    props: ['csrf', 'link_cws','link_event'],
    components: {
        'el-tiptap': ElementTiptap,
        QrcodeVue,
    },
    data() {
        return {
            pickerOptions: {
                disabledDate(time) {
                    return time.getTime() < Date.now();
                },
            },
            qrScale: 15,
            dataLink: [],
            formLabelWidth: '120px',
            activeName: 'first',
            size: 300,
            image: '',
            dialogQrCode: false,
            dialogJoinEvent: false,
            dialogLinks : false,
            currentPage: 1,
            totalNumber: 0,
            page: 1,
            formSearch: {
                name: '',
                status: '',
            },
            tableData: [],
            rules: {
                banner_url: [{
                    required: true,
                    message: 'Hãy upload ảnh',
                    trigger: ['change']
                }],
                name: [{
                    required: true,
                    message: 'Please input name',
                    trigger: ['blur', 'change']
                }]
            },
            dataSessions:[],
            dataBooths:[],
            form : {
                id : '',
                banner_url : '',
                name : '',
                lat : '',
                lng : '',
                address : '',
                description : '',
                start_at : '',
                end_at : '',
                type : 1,
                order : '',
                task_galleries: [],
            },
        }
    },
    methods: {
        handlePreviewEvent(scope, row){
            window.location.href = '/events/preview/'+ row.id;
        },
        create(){
            window.location.href = '/events/create';
        },
        changeStatusDetail(status,id){
            const loading = this.$loading({
                lock: true,
                text: 'Loading',
                spinner: 'el-icon-loading',
                background: 'rgba(0, 0, 0, 0.7)'
            });
            let rawData =
                {
                    'status': status,
                    'id': id,
                }
            axios.post(this.link_cws+'/api/events/change-status-detail', rawData).then(e => {
                Notification.success({
                    title: ' Thành công',
                    message: ' Thành công',
                    type: 'success',
                });
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
        },

        handleJoinEvent(scope, row) {
            console.log(window.location.href);
            window.location.href = this.link_cws+"/events/"+row.id;
        },

        handleLink(scope, row) {
            this.dataLink = row.task_generate_links;
            this.dialogLinks = true
        },
        downloadQrCode(id){
            let canvasImage = document.getElementById(id).querySelector('canvas').toDataURL('image/png');
            // this can be used to download any image from webpage to local disk
            let xhr = new XMLHttpRequest();
            xhr.responseType = 'blob';
            xhr.onload = function () {
                let a = document.createElement('a');
                a.href = window.URL.createObjectURL(xhr.response);
                a.download = 'qrc'+id+'.png';
                a.style.display = 'none';
                document.body.appendChild(a);
                a.click();
                a.remove();
            };
            xhr.open('GET', canvasImage); // This is to download the canvas Image
            xhr.send();
        },
        handleClickTab(tab, event) {
            console.log(tab, event);
        },
        handleQrCode(scope, row){
            this.dialogQrCode = true
            const loading = this.$loading({
                lock: true,
                text: 'Loading',
                spinner: 'el-icon-loading',
                background: 'rgba(0, 0, 0, 0.7)'
            });
            let url = this.link_cws+'/api/tasks-cws/edit/'+ row.id;
            axios.get(url).then(e => {
                if (e.data.data.message.booths !== null){
                    this.dataBooths = e.data.data.message.booths.detail;
                }else {
                    this.dataBooths = []
                }
                if (e.data.data.message.sessions !== null){
                    this.dataSessions = e.data.data.message.sessions.detail;
                } else {
                    this.dataSessions = []
                }

                loading.close();

            }).catch((_) => {
                loading.close();
            })
        },
        handleRemove(file, fileList) {
            this.form.task_galleries = fileList
        },
        handlePreview(file) {
            console.log(file);
        },
        handleSuccess( file,fileList) {
            this.form.task_galleries.push(
                file
            )

        },
        handleSizeChange(val) {
            this.list_data(val, false);
        },
        handleCurrentChange(val) {
            this.list_data(val);
        },
        handleAvatarSuccess(res, file) {
            this.form.banner_url = URL.createObjectURL(file.raw);
            this.form.banner_url = res;
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
            let url =this.link_cws+'/api/events'
            axios.get(url, {
                params: rawData
            }).then(e => {
                this.tableData = e.data.data;
                self.totalNumber = e.data.total;
                loading.close();

            }).catch((_) => {
                loading.close();
            })
        },
        changeStatus(status,id){
            const loading = this.$loading({
                lock: true,
                text: 'Loading',
                spinner: 'el-icon-loading',
                background: 'rgba(0, 0, 0, 0.7)'
            });
            let rawData =
                {
                    'status': status,
                    'id': id,
                }
            axios.post(this.link_cws+'/api/events/change-status', rawData).then(e => {
                Notification.success({
                    title: ' Thành công',
                    message: ' Thành công',
                    type: 'success',
                });
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
        },
        handleDelete(scope, row){
            this.$confirm('Bạn có muốn xóa không ?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Hủy',
                type: 'warning'
            }).then(() => {
                axios.delete(this.link_cws+'/api/events/'+row.id, ).then(e => {
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
    },
    mounted: function () {
        this.list_data()
    },
}
</script>

<style scoped>
    .form-style{
        padding: 10px;
        border: 1px solid #DCDFE6 ;
    }

    #el-drawer__title {
        margin-bottom: 0;
        background-color: #083777!important;
        color: #fff;

        span {
            color: #fff;
        }
    }

    .formCreate {
        padding: 20px 30px;
    }
</style>
