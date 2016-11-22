import React from 'react';
import { render } from 'react-dom';
import { Divider, Button, Modal } from 'semantic-ui-react'
import GridContainer from '../helpers/grid-container.jsx';

export default class ModalViewTasks extends React.Component {
    constructor(props, context) {
        super(props, context);

        this.state = {
            modalOpen: false,
            modalLoading: true,
            modalRows: []
        };

        this.openModal = this.openModal.bind(this);
        this.closeModal = this.closeModal.bind(this);
        this.updateRows = this.updateRows.bind(this);
    }

    componentWillMount() {
        this.updateRows(this.props.customerId);
    }

    componentWillReceiveProps(props) {
        if (this.props.customerId != props.customerId) {
            this.updateRows(props.customerId);
        }
    }

    updateRows(customerId) {
        let self = this;

        this.setState({"modalRows": []});

        axios.get("/api/task?customerId=" + customerId).then(function (response) {
            self.setState({"modalRows": response.data});
        }).catch(function(error) {
            console.log(error);
        });
    }

    openModal() {
        this.state.openModal = true;
    }

    closeModal() {
        this.state.openModal = false;
    }

    render() {
        return <Modal trigger={<Button onClick={this.openModal} secondary>View tasks
            ({this.state.modalRows.length})</Button>}>
            <Divider horizontal>Tasks</Divider>

            <Modal.Content>
                <GridContainer
                    fields={['name']}
                    columns={['Name']}
                    rows={this.state.modalRows}
                    viewRow={function () {
                        console.log("MOI");
                    }}/>
            </Modal.Content>
        </Modal>
    }
}
