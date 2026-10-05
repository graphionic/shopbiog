import { Component } from "@wordpress/element";
import { __ } from "@wordpress/i18n";

/**
 * Error Boundary Component
 * Catches JavaScript errors anywhere in the child component tree
 */
class ErrorBoundary extends Component {
	constructor(props) {
		super(props);
		this.state = { hasError: false, error: null, errorInfo: null };
	}

	static getDerivedStateFromError(error) {
		// Update state so the next render will show the fallback UI
		return { hasError: true };
	}

	componentDidCatch(error, errorInfo) {
		// Log error details
		console.error("Smart Design Library Error:", error, errorInfo);

		this.setState({
			error: error,
			errorInfo: errorInfo,
		});

		// Optional: Send error to logging service
		if (this.props.onError) {
			this.props.onError(error, errorInfo);
		}
	}

	render() {
		if (this.state.hasError) {
			// Fallback UI
			return (
				<div className="sp-real-patterns-error-boundary">
					<div className="sp-real-patterns-error-content">
						<h3>{__("Something went wrong", "testimonial-free")}</h3>
						<p>
							{__(
								"We're sorry, but something went wrong while loading the design library.",
								"testimonial-free"
							)}
						</p>

						{this.props.showDetails && this.state.error && (
							<details className="sp-real-patterns-error-details">
								<summary>{__("Error Details", "testimonial-free")}</summary>
								<pre>{this.state.error.toString()}</pre>
								{this.state.errorInfo && <pre>{this.state.errorInfo.componentStack}</pre>}
							</details>
						)}

						<button
							className="sp-real-patterns-retry-button"
							onClick={() => {
								this.setState({
									hasError: false,
									error: null,
									errorInfo: null,
								});
								if (this.props.onRetry) {
									this.props.onRetry();
								}
							}}
						>
							{__("Try Again", "testimonial-free")}
						</button>
					</div>
				</div>
			);
		}

		return this.props.children;
	}
}

export default ErrorBoundary;
