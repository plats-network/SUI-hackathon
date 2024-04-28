import React from 'react';

const StatusSessionComponent = ({ id, detailId }) => {
    const handleClick = () => {
        console.log('Label clicked with id:', id);
        console.log('Label clicked with detailId:', detailId);
        // Thực hiện các thao tác khác khi click vào label
    };

    return (
        <td width="10%">
            <input
                type="checkbox"
                id={`session_${id}`}
                switch="none"
                defaultChecked={true} // Sử dụng giá trị mặc định tùy thuộc vào trạng thái của checkbox
            />
            {/* Gắn sự kiện click vào label và sử dụng props truyền từ Blade template */}
            <label
                className="job"
                data-id={id}
                data-detail-id={detailId}
                htmlFor={`session_${id}`}
                data-on-label="On"
                data-off-label="Off"
                onClick={handleClick}
            />
        </td>
    );
};

export default StatusSessionComponent;
