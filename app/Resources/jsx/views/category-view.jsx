import React from 'react';
import { render } from 'react-dom';
import CategoryRow from '../rows/category-row.jsx';
import TextField from '../fields/text.jsx';
import NavigationButton from '../components/navigation-button.jsx';

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
                        <NavigationButton show={this.props.showNext} float="right" onClick={this.props.nextRow} leftLabel="Next" rightLabel="" icon="angle double left icon" />
                        <NavigationButton show={this.props.showPrev} float="left" onClick={this.props.previousRow} leftLabel="" rightLabel="Previous" icon="angle double right icon" />

                        <h1 style={{width: "125px", margin: "0 auto", textAlign: "center"}} className="mdl-card__title-text">Category</h1>
                    </div>

                    <div className="form-separator">
                        <h2 className="">General info</h2>
                    </div>

                    <div className="one field">
                        <TextField pos="left" name="name" label="Name" value={this.props.row.name} handleChange={this.props.handleChange} />
                    </div>

                    <div className="">
                        <NavigationButton show={true} float="right" onClick={this.props.closeRow} leftLabel="Close" rightLabel="" icon="clear" />
                        <NavigationButton show={true} float="left" onClick={this.props.handleSubmit} leftLabel="Save" rightLabel="" icon="done" />
                    </div>
                </div>
            </div>
        } else if (this.props.data.length > 0) {
            let rows = [];

            this.props.data.forEach(function(category) {
                rows.push(<CategoryRow category={category} key={category.id} viewRow={self.props.viewRow} />);
            });

            return <div className="ui four column celled grid">
                <div className="row">
                    <div className="column">name</div>
                </div>

                {rows}
            </div>
        }
    }
}
