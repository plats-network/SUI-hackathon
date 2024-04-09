<template>
    <el-row>
        <el-row class="mb-1">
            <h4>Task management</h4>
            <el-button style="float: right;margin: 5px"
                type="primary"
                @click="handleCreate()">
                <i class="el-icon-plus"></i> Create Task
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
                                style="width: 40%; height: auto;"
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
<!--                    prop="description"-->
<!--                    label="Description"-->
<!--                    width="180">-->
<!--                </el-table-column>-->
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
                            type="success"
                            @click="handleLink(scope.$index, scope.row)">
                            <i class="el-icon-paperclip"></i>
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
                @current-change="handleCurrentChange"
                :page-size="10"
                :current-page.sync="currentPage"
                layout="total, prev, pager, next"
                :total="this.totalNumber"
                class="float-right mt-3 text-center"
            >
            </el-pagination>
            <el-dialog title="Link Share" :visible.sync="dialogLinks">
                <el-table :data="dataLink">
                    <el-table-column property="name" label="Name" width="200"></el-table-column>
                    <el-table-column property="url" label="Url" ></el-table-column>
                    <el-table-column label="Copy" width="80">
                        <template slot-scope="scope">
                            <el-button size="mini" type="success" @click="copyCode(scope.$index, scope.row)"><i class="el-icon-paperclip"></i>
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </el-dialog>
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
    name: "Index",
    props: ['link_cws'],
    components: {
        'el-tiptap': ElementTiptap,
    },
    data() {
        return {
            dataLink: [],
            dialogLinks : false,
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
        }
    },
    methods: {
        copyCode(scope, row){
            // console.log(row.url)
            // row.url.select();
            // document.execCommand("copy");
        },
        handleDelete(scope, row){
            this.$confirm('Bạn có muốn xóa không ?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Hủy',
                type: 'warning'
            }).then(() => {
                axios.get(this.link_cws+'/api/tasks-cws/delete/'+row.id, ).then(e => {
                    this.list_data()
                }).catch((_) => {
                })
            }).catch(() => {
            });
        },
        list_data(val = 1) {
            let rawData =
                {
                    'page': this.page,
                    'name': this.formSearch.name,
                    'description': this.formSearch.description,
                    'status': this.formSearch.status
                }
            this.page = val;
            rawData['page'] = val;
            const loading = this.$loading({
                lock: true,
                text: 'Loading',
                spinner: 'el-icon-loading',
                background: 'rgba(0, 0, 0, 0.7)'
            });
            let url = '/api/tasks-cws'
            axios.get(url, {
                params: rawData
            }).then(e => {
                console.log(e.data)
                this.tableData = e.data.data;
                this.totalNumber = e.data.meta.total;
                loading.close();

            }).catch((_) => {
                loading.close();
            })
        },
        handleEdit(scope, row) {
            window.location.href = '/tasks/edit/'+ row.id;
        },
        handleLink(scope, row) {
           this.dataLink = row.links;
           this.dialogLinks = true
        },
        handleCreate() {
            window.location.href = '/tasks/create';
        },
        handleCurrentChange(val) {
            this.list_data(val);
        },
    },
    mounted: function () {
        this.list_data()
    },
}
</script>

<style scoped>

</style>
