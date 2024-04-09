<template>
    <el-row>
        <el-row class="mb-1">
            <h4>User Join Event</h4>
            <div class="d-flex justify-content-between mb-2">
                <el-button type="primary" size="mini" @click="exportData()">Excel <i class="el-icon-download el-icon-right"></i></el-button>
                <a href="/events" ><el-button size="mini" class="mb-1" type="primary" icon="el-icon-back"></el-button></a>
            </div>
            <el-descriptions title="" :column="3" border>
                <el-descriptions-item label="Name"  label-class-name="my-label" content-class-name="my-content">
                    <el-col :span="23">
                        <el-input placeholder="typing ..." v-model="formSearch.name" @change="list_data()"></el-input>
                    </el-col>
                </el-descriptions-item>
                <el-descriptions-item label="Phone" label-class-name="my-label" content-class-name="my-content">
                    <el-col :span="23">
                        <el-input placeholder="typing ..." v-model="formSearch.phone" @change="list_data()"></el-input>
                    </el-col>
                </el-descriptions-item>
                <el-descriptions-item label="Email" label-class-name="my-label" content-class-name="my-content">
                    <el-col :span="23">
                        <el-input placeholder="typing ..." v-model="formSearch.email" @change="list_data()"></el-input>
                    </el-col>
                </el-descriptions-item>
            </el-descriptions>
        </el-row>
        <el-row>
            <el-table border
                :data="tableData"
                style="width: 100%">
                <el-table-column type="index" width="50"></el-table-column>
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
                    prop="phone"
                    label="Phone" width="180"
                >
                </el-table-column>
                <el-table-column  width="110"  prop="compare_session"
                                  label="Done/Total (Session)">
                </el-table-column>
                <el-table-column  width="130"  prop="sesion_code"
                                  label="Session Code">
                </el-table-column>
                <el-table-column  width="110" prop="compare_booth"
                                  label="Done/Total (Booth)">

                </el-table-column>
                <el-table-column  width="130" prop="booth_code"
                                      label="Booth Code">

                </el-table-column>
                <el-table-column  width="180"
                                  label="Status">
                    <template slot-scope="scope">
                        <el-tag type="success" v-if="scope.row.is_checkin == true">Check In</el-tag>
                        <el-tag type="danger" v-if="scope.row.is_checkin == false">Join</el-tag>
                    </template>
                </el-table-column>
                <el-table-column  width="180"
                                  label="Type">
                    <template slot-scope="scope">
                        <el-tag type="success" v-if="scope.row.type == 0">User</el-tag>
                        <el-tag type="danger" v-if="scope.row.type == 1">Guest</el-tag>
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
                :total="this.totalNumber"
                class="float-right mt-3 text-center"
            >
            </el-pagination>
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
    name: "event-user",
    props: ['task_id','link_cws'],
    components: {
        'el-tiptap': ElementTiptap,
    },
    data() {
        return {
            formSearch: {
                name: '',
                phone: '',
                email: '',
            },
            tableData: [],
            currentPage: 1,
            totalNumber: 0,
        }
    },
    methods: {
        exportData() {
            var rawData =
                {
                    'task_id': this.task_id,
                }
            const loading = this.$loading({
                lock: true,
                text: 'Loading',
                spinner: 'el-icon-loading',
                background: 'rgba(0, 0, 0, 0.7)'
            });
            axios.post(this.link_cws+'/export/user-join-event', rawData,{responseType: 'arraybuffer'}).then(response => {
                var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                var fileLink = document.createElement('a');
                fileLink.href = fileURL;
                fileLink.setAttribute('download', 'user-join-event.xlsx');
                document.body.appendChild(fileLink);
                fileLink.click();
                loading.close();
            }).catch((_) => {
                loading.close();
            })
        },
        list_data(val = 1, type = true) {
            var self = this;
            let rawData =
                {
                    'name': this.formSearch.name,
                    'email': this.formSearch.email,
                    'phone': this.formSearch.phone
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
            let url = this.link_cws+'/events/api/'+this.task_id
            axios.get(url, {
                params: rawData
            }).then(e => {
                this.tableData = e.data.data;
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
    }
}
</script>

<style scoped>

</style>
