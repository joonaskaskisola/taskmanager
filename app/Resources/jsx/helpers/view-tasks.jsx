import React from 'react';
import { render } from 'react-dom';
import { Divider, Button, Modal } from 'semantic-ui-react'
import GridContainer from '../helpers/grid-container.jsx';
import Row from '../components/row.jsx';

export default class ModalViewTasks extends React.Component {
    constructor(props, context) {
        super(props, context);

        this.state = {
            modalOpen: false,
            modalLoading: true,
            rows: []
        };

        // axios.get("/api/tasks").then(function (response) {
        //     response.data.forEach(function (row) {
        //         this.state.rows.push(<Row fields={['name']} row={row} key={row.id} viewRow={function(rowId) { console.log(rowId); }}/>);
        //     });
        // }).catch(function(error) {
        //     console.log(error);
        // });

        this.openModal = this.openModal.bind(this);
        this.closeModal = this.closeModal.bind(this);
    }

    openModal() {
        this.state = { openModal: true };
    }

    closeModal() {
        this.state = { openModal: false };
    }

    render() {
        let columns = ['Name'];

        return (
            <Modal trigger={<Button onClick={this.openModal} secondary>View tasks</Button>}>
                <Divider horizontal>Tasks</Divider>

                <Modal.Content>
                    <GridContainer rows={rows} columns={columns}/>
                </Modal.Content>
            </Modal>
        )
    }
}
