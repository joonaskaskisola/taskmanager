import React from 'react';
import { render } from 'react-dom';
import Row from '../components/row.jsx';
import TextField from '../components/text.jsx';
import GridContainer from '../helpers/grid-container.jsx';
import { Divider } from 'semantic-ui-react';
import NavigationButtons from '../helpers/navigation-buttons.jsx';

export default class CountryView extends React.Component {
    constructor(props, context) {
        super(props, context);
    }

    render() {
        let self = this;

        if (this.props.loading) {
            return <div></div>
        }

        if (this.props.row) {
            return <div>
                <Divider horizontal>Country</Divider>

                <div className={"ui form " + (this.props.loading ? "loading" : "")}>
                    <NavigationButtons
                        header={true}
                        nextRow={this.props.nextRow}
                        previousRow={this.props.previousRow}
                        showPrev={this.props.showPrev}
                        showNext={this.props.showNext}/>

                    <h4 className="ui dividing header">General Information</h4>

                    <div className="field">
                        <TextField pos="left" name="name" label="Name" value={this.props.row.name} handleChange={this.props.handleChange} />
                    </div>
                    <div className="field">
                        <TextField pos="right" name="code" label="Code" value={this.props.row.code} handleChange={this.props.handleChange} />
                    </div>

                    <NavigationButtons
                        footer={true}
                        closeRow={this.props.closeRow}
                        handleSubmit={this.props.handleSubmit}/>
                </div>
            </div>
        } else if (this.props.data.length > 0) {
            let rows = [],
                columns = ['Name', 'Code'];

            this.props.data.forEach(function (row) {
                rows.push(<Row fields={['name', 'code']} row={row} key={row.id} viewRow={self.props.viewRow}/>);
            });

            return <GridContainer rows={rows} columns={columns} createNew={this.props.createNew}/>
        }
    }
}
