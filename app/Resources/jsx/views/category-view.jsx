import React from 'react';
import { render } from 'react-dom';
import Row from '../components/row.jsx';
import TextField from '../components/text.jsx';
import NavigationButton from '../components/navigation-button.jsx';
import GridContainer from '../components/grid-container.jsx';

export default class CategoryView extends React.Component {
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
                <div className={"ui form " + (this.props.loading ? "loading" : "")}>
                    <div className="">
                        <NavigationButton show={this.props.showNext} float="right" onClick={this.props.nextRow} leftLabel="Next" rightLabel="" icon="double right angle icon" />
                        <NavigationButton show={this.props.showPrev} float="left" onClick={this.props.previousRow} leftLabel="" rightLabel="Previous" icon="double left angle icon" />

                        <h1 style={{width: "125px", margin: "0 auto", textAlign: "center"}}>Category</h1>
                    </div>

                    <h4 className="ui dividing header">General Information</h4>

                    <div className="one field">
                        <TextField pos="left" name="name" label="Name" value={this.props.row.name} handleChange={this.props.handleChange} />
                    </div>

                    <div className="navigation-footer-buttons">
                        <NavigationButton show={true} float="right" onClick={this.props.closeRow} leftLabel="Close" rightLabel="" icon="clear" />
                        <NavigationButton show={true} float="left" onClick={this.props.handleSubmit} leftLabel="Save" rightLabel="" icon="done" />
                    </div>
                </div>
            </div>
        } else if (this.props.data.length > 0) {
            let rows = [],
                columns = ['Name'];

            this.props.data.forEach(function (row) {
                rows.push(<Row fields={['name']} row={row} key={row.id} viewRow={self.props.viewRow}/>);
            });

            return <GridContainer rows={rows} columns={columns} createNew={this.props.createNew}/>
        }
    }
}
